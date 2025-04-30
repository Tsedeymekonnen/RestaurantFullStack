<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class OrderController extends Controller
{
    // Store a new order
    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric',
            'status' => 'in:pending,completed,cancelled', // Ensure valid status
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        $order = Order::create([
            'menu_id' => $request->menu_id,
            'user_id' => $user->id,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'status' => $request->status ?? 'pending', // Default to pending
        ]);

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order
        ], 201);
    }

    // Fetch orders (admin sees all, user sees only their own)
    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $status = $request->query('status'); // Optional status filter
        $today = $request->query('today'); // Optional filter for today's orders
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($user->role === 'admin') {
            // Admin can see all orders, eager load both menu and user
            $orders = Order::with(['menu', 'user']);
        } else {
            // Regular users see only their own orders, eager load menu
            $orders = Order::with('menu', 'user')->where('user_id', $user->id);
        }

        if ($status) {
            $orders->where('status', $status);
        }

        if ($today) {
            $orders->whereDate('created_at', Carbon::today());
        }

        if ($startDate && $endDate) {
            $orders->whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);
        } elseif ($startDate) {
            $orders->whereDate('created_at', Carbon::parse($startDate)->startOfDay());
        }

        // Hide orders older than one week unless explicitly searched for
        if (!$startDate && !$endDate) {
            $orders->where('created_at', '>=', Carbon::now()->subWeek());
        }

        return response()->json($orders->get());
    }

    public function updateStatus(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $order = Order::findOrFail($id);
    
        // Validate request status
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);
    
        // Check cancellation rules
        if ($request->status === 'cancelled') {
            // Allow admins to cancel any order
            if ($user->role !== 'admin' && $order->user_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized. You can only cancel your own orders.'], 403);
            }
    
            // Check the cancellation deadline (before 4:00 PM on the same day)
            $orderDate = Carbon::parse($order->created_at);
            $cutoffTime = $orderDate->copy()->setTime(10, 0, 0); // 10:00 PM Ethiopia time
            if (Carbon::now()->gt($cutoffTime)) {
                return response()->json(['error' => 'Orders can only be cancelled before 4:00 PM on the day they were created'], 400);
            }
        } 
        // Other status updates are restricted to admins
        else if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $order->status = $request->status;
        $order->save();
    
        return response()->json(['message' => 'Order status updated', 'order' => $order]);
    }
    
}
