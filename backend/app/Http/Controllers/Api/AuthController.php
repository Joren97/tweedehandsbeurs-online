<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    /**
     * Create User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(StoreUserRequest $request)
    {
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
        ]);
        $user['token'] = $user->createToken('apiToken')->plainTextToken;
        return $this->successResponse($user, "User created successfully", 201);
    }

    /**
     * Login The User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required',
                    'remember' => 'boolean'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            // Check if email exists
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $error = [
                    'email' => 'Emailadres bestaat niet'
                ];
                return $this->fieldErrorResponse($error);
            }

            // Check combination of email and password
            if (!Auth::attempt($request->only(['email', 'password']))) {
                $error = [
                    'email' => 'Wachtwoord is onjuist'
                ];
                return $this->fieldErrorResponse($error);
            }

            $user = User::where('email', $request->email)->first();

            // Get role of user
            $role = $user->role;

            // Delete all old tokens
            $user->tokens()->delete();
            $user['token'] = $user->createToken("apiToken", [$role])->plainTextToken;
            $user['remember'] = $request->remember;

            return $this->successResponse($user, "Gebruiker werd aangemeld", 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function userinfo()
    {
        $user = auth()->user();
        return $this->successResponse($user, "User info", 200);
    }
}