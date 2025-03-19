<?php

namespace Shipyard\Middleware;

use Shipyard\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (!Auth::user()->userIs($role)) {
            abort(403);
        }

        return $next($request);
    }
}
