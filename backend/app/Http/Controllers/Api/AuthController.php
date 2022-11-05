<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\auth\ValidateTokenRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\PasswordResets;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\ForgotPasswordRequest;
use App\Http\Requests\auth\ResetPasswordRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
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
        $user = User::create(
            [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
            ]
        );
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
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required',
                    'remember' => 'boolean'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => $validateUser->errors()
                    ],
                    401
                );
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
            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function userinfo()
    {
        $user = auth()->user();
        return new UserResource($user);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->phone_number = $request->phoneNumber;
        $user->member_number = $request->memberNumber;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->postal_code = $request->postalCode;
        $user->save();
        return $this->successResponse($user, "User info updated", 200);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        PasswordResets::where('email', $request->email)->delete();

        $tokenData = PasswordResets::create($request->data());

        Mail::to($request->email)->send(new SendCodeResetPassword($tokenData->token));

        return $this->successResponse([], "Email sent", 200);
    }

    public function validateForgotPasswordToken(ValidateTokenRequest $request)
    {
        $passwordReset = PasswordResets::firstWhere('token', $request->token);

        if ($passwordReset->isExpire()) {
            return $this->errorResponse("Your token is expired", 401);
        }

        return $this->successResponse([], "Token is valid", 200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $passwordReset = PasswordResets::firstWhere('token', $request->token);

        if ($passwordReset->isExpire()) {
            return $this->errorResponse("Your token is expired", 401);
        }

        $user = User::firstWhere('email', $passwordReset->email);

        $user->update(array('password' => Hash::make($request->password)));

        $passwordReset->delete();

        return $this->successResponse([], "Password reset", 200);
    }
}