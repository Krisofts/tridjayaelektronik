<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$groups
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
        | GROUP CHECK
        |--------------------------------------------------------------------------
        */

        if (! $user->inGroup(...$groups)) {
            abort(403);
        }

        return $next($request);
    }
}