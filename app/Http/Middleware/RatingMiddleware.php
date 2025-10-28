<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RatingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $ratingLimit = 5): Response
    {
        // Assuming user rating is stored in user model or session
        // For demo purposes, we'll assume it's in the authenticated user
        $user = $request->user();

        if ($user && $user->rating >= $ratingLimit) {
            return response()->json(['error' => 'Access denied due to high rating'], 403);
        }

        return $next($request);
    }
}