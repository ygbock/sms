<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * Roles may be provided as a comma-separated list.
     */
    public function handle(Request $request, Closure $next, $roles = null)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!$roles) {
            return $next($request);
        }

        $allowed = array_map('trim', explode(',', $roles));
        $userRole = optional($request->user()->role)->name;

        if (!in_array($userRole, $allowed)) {
            return response()->json(['message' => 'Forbidden. Insufficient role.'], 403);
        }

        return $next($request);
    }
}
