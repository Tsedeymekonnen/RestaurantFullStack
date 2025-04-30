<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create user
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Return success response
        return response()->json(['message' => 'User registered successfully'], 201);
    }
    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Get credentials from the request
        $credentials = $request->only('email', 'password');
    
        // Attempt to authenticate the user
        if (auth()->attempt($credentials)) {
            // If successful, generate the JWT token
            $user = auth()->user(); // Get the authenticated user
            $token = JWTAuth::fromUser($user); // Generate JWT token
    
            // Return the token and user as a JSON response
            return response()->json([
                'token' => $token,
                'user' => $user // Include the user object
            ]);
        }
    
        // If authentication fails, return an error message
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
}


