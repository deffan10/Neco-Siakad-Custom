<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubroleIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $subrole)
    {
        $activeSubrole = session('active_subrole');

        if ($activeSubrole !== $subrole) {
            abort(403, 'Unauthorized - Subrole tidak diizinkan');
        }

        return $next($request);
    }
}
