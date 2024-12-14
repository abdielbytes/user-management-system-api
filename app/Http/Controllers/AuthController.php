<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken($request->email)->accessToken;
        return response()->json([
            'message' => 'success',
            'user'=> $user,
            'token'=> $token
            ]);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => '<PASSWORD>',
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $user = User::where('email', $request->email)->first();

            if (Auth::attempt($request->only('email', 'password'))) {
                $user->tokens->each(function ($token) {
                    $token->delete();
                });

     

                $token = $user->createToken($request->email)->accessToken;
                $user->last_activity = now();

                $user->save();
                DB::commit();
                return response()->json([
                    'success' => true,
                    "userData" => $user,
                    'message' => 'Login successful',
                    'token' => $token,
                    'token_type' => 'Bearer',
                ], 200);
            }

            $userEmailExist = User::where('email', $request->email)->exists();

            DB::commit();
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials',
            ], 401);
        } catch (\Exception $e) {

            DB::rollBack();


            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }


    }
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user){
            $user->token->each(function($token){
                $token->delete();
            });
        
            return response()->json([
                'message' => 'Successfully logged out',
                'success' => true,
            ], 200);
        }
        return response()->json([
            'message'=>'User not authenticated',
            'succeess' => false,
        ], 401);
    }
}
