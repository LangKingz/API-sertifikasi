<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function register(Request $request)
    {
        try {
            $vaidator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|max:8|min:8'
            ]);

            if ($vaidator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $vaidator->errors()
                ], 400);
            }

            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $data = User::where('email', $request->email)->first();
            $tokenResult = $data->createToken('authToken')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'success register',
                'type_token' => 'Bearer',
                'access_token' => $tokenResult,
                'user' => $data

            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()
                ]);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'success login',
                'type_token' => 'Bearer',
                'access_token' => $token,
                'user' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->currentAccessToken()->delete();

            return response()->json([
                'status' => 200,
                'message' => 'success logout'
            ]);
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'message' => $th
            ]);
        }
    }
}
