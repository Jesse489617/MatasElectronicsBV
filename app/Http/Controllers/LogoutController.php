<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LogoutUserRequest;

class LogoutController extends Controller
{
    public function __invoke(LogoutUserRequest $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
