<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Block write access for users who don't have management rights for this module.
     * All users can READ (GET). Only authorized roles can WRITE (POST/PUT/DELETE).
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect('/login');
        }

        if (!$user->hasAccessTo($module)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'unauthorized',
                    'message' => 'This action requires a higher access level.',
                    'current_role' => $user->role->label(),
                    'required_module' => $module,
                ], 403);
            }

            return response()->view('auth.access-denied', [
                'currentRole' => $user->role->label(),
                'requiredModule' => $module,
            ], 403);
        }

        return $next($request);
    }
}
