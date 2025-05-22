<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // GET /api/users – Fetch all users
    public function index()
    {
        return response()->json(User::select('id', 'email', 'created_at')->get());
    }

    // PUT /api/users/{id} – Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
           
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }

        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully']);
    }

    // DELETE /api/users/{id} – Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
