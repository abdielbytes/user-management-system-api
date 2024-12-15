<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('UserToken')->accessToken;

        return response()->json([
            'message' => 'success',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials',
            ], 401);
        }

        $user = Auth::user();
        $user->tokens->each(function ($token) {
            $token->delete();
        });

//        $token = $user->createToken('UserToken')->accessToken;
        $token = $user->createToken('Personal Access Token')->accessToken;

        $user->last_activity = now();
        $user->save();

        return response()->json([
            'success' => true,
//            'userData' => $user,
            'message' => 'Login successful',
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'message' => 'Successfully logged out',
                'success' => true,
            ], 200);
        }

        return response()->json([
            'message' => 'User not authenticated',
            'success' => false,
        ], 401);
    }
}
