<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Handle user login and return Sanctum token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::warning('Failed login attempt', ['email' => $request->email]);
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        Log::info('User logged in', ['user_id' => $user->id, 'email' => $user->email]);

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        Log::info('User logged out', ['user_id' => $request->user()->id]);

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get current authenticated user
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
