<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Grant panel access to anyone with an admin role/permission, keeping
        // the legacy is_admin flag as a fallback during the transition.
        if ($user && ($user->can('access admin') || $user->is_admin)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }

}
