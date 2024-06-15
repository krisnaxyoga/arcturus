<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $separator_exists = Str::contains($roles[0],'|');

        if ($separator_exists) {
            $roles = explode('|', $roles[0]);
        }

        if (in_array(auth()->user()->role_id, $roles)) {
            return $next($request);
        }

        return redirect('/redirect');
    }
}
