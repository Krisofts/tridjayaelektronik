<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$permissions
    ): Response {

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | NOT AUTHENTICATED
        |--------------------------------------------------------------------------
        */

        if (! $user) {
            abort(401);
        }

        /*
        |--------------------------------------------------------------------------
        | PERMISSION CHECK
        |--------------------------------------------------------------------------
        */

        if (! $user->canAccess(...$permissions)) {
            abort(403);
        }

        return $next($request);
    }
}