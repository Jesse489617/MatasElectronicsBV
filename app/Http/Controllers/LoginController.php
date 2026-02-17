<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\AuthUserRequest;
use App\Http\Requests\auth\LogoutUserRequest;
use App\Http\Requests\auth\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(AuthUserRequest $request)
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
}
