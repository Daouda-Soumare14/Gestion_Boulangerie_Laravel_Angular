<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\UserFormRequet;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserFormRequet $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data)->assignRole('CLIENT');

            $token = $user->createToken("auth_token")->plainTextToken;

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Inscription reussi.',
                'data' => $user,
                'token' => $token,
                'role' => $user->getRoleNames()->first(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 200,
                'status_message' => $e->getMessage(),
            ]);
        }
    }


    public function login(UserFormRequet $request)
    {
        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = auth()->user();

                $token = $user->createToken("auth_token")->plainTextToken;

                return response()->json([
                    'status_code' => 200,
                    'status_message' => 'Connection reussi.',
                    'data' => $user,
                    'role' => $user->getRoleNames()->first(),
                    'token' => $token
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 200,
                'status_message' => $e->getMessage(),
            ]);
        }
    }


    public function logout(Request $request)
    {
        try {
            $user = $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Deconnection reussi.',
                'data' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 200,
                'status_message' => $e->getMessage(),
            ]);
        }
    }


    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'role' => $user->getRoleNames()->first(),
        ]);
    }
}
