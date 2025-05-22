<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
   public function register(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
        'personal_area' => 'required|in:Coffee,Daleti,Head Office,Mogle,Transport,Zaki',
        'floor' => 'required|in:Ground,Mid,First,Second,Third,Fourth,Fifth',
        'department' => 'required|in:Administrator,Internal Audit,Default,Export,Finance,Fleet operation,General service,Human Resource,ICT,MD office,Monitoring and Evaluation,Organizational Development,President office,Production,Quality,Procurement,Sales and Marketing,Warehouse,Technic'
    ]);

    $user = User::create([
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'personal_area' => $request->personal_area,
        'floor' => $request->floor,
        'department' => $request->department
    ]);

    return response()->json(['message' => 'User registered successfully'], 201);
}
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = JWTAuth::fromUser($user);
    
            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }
    
        return response()->json(['error' => 'Unauthorized'], 401);
    }

public function changePassword(Request $request)
{
    $user = auth()->user();

    $validator = Validator::make($request->all(), [
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['error' => 'Current password is incorrect'], 403);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json(['message' => 'Password changed successfully']);
}

}