<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AdminWebMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (! $token) {
            return redirect('/'); // or abort(403)
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (! $accessToken || ! $accessToken->tokenable->is_admin) {
            return redirect('/'); // or abort(403)
        }

        // Optionally: set user in request for downstream
        $request->setUserResolver(fn () => $accessToken->tokenable);

        return $next($request);
    }
}
