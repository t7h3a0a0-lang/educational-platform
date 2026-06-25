<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?? $request->input('api_token') ?? $request->header('X-API-TOKEN');

        if (!$token) {
            return response()->json(['message' => 'API Token is missing.'], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid API Token.'], 401);
        }

        // Set the authenticated user for the request lifecycle
        Auth::setUser($user);

        return $next($request);
    }
}
