<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('AdminMiddleware user:', ['user' => $request->user()]);

        if (! $request->user() || ! $request->user()->is_admin) {
            \Log::warning('Unauthorized access attempt', ['user_id' => $request->user()?->id]);

            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
