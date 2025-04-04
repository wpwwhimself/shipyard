<?php

namespace App\Http\Middleware\Shipyard;

use App\Models\Shipyard\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (empty($role)) $next($request);
        if (!User::hasRole($role) && !User::hasRole("super")) {
            abort(403);
        }

        return $next($request);
    }
}
