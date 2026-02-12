<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\AuthUserRequest;
use App\Http\Requests\auth\StoreUserRequest;
use App\Http\Requests\auth\LogoutUserRequest;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Account has been created successfully.',
        ]);
    }

    public function auth(AuthUserRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'These credentials do not match our records.',
            ], 401);
        }

        return response()->json([
            'message' => 'Logged in successfully.',
            'user' => $user,
            'currentToken' => $user->createToken('new_user')->plainTextToken,
        ]);
    }

    public function logout(LogoutUserRequest $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
