<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;

class EmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
            $response = [
                'data' => [
                    'code' => 403,
                    'message' => 'Your email address is not verified.'
                ]
            ];

            return response()->json($response, 403);
        }

        return $next($request);
    }
}
