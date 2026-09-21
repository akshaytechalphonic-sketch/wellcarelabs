<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Ensure the authenticated user has one of the allowed roles.
     *
     * Usage in routes: ->middleware('role:admin') or 'role:admin,hospital_manager'
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
