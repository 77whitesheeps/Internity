<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Retrieve the currently authenticated user.
        $user = $request->user();

        // Reject unauthenticated users.
        if (!$user) {
            abort(401, 'Authentication is required.');
        }

        // Retrieve the user's assigned role.
        $userRole = $user->role->name;

        // Check whether the user's role is permitted.
        if (!in_array($userRole, $roles, true)) {
            abort(403, 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}
