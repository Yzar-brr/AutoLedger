<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    if (auth()->check() && !auth()->user()->is_approved) {
        auth()->logout();
        return redirect()->route('login')->with('error', 'Votre compte est en attente de validation par un administrateur.');
    }

    return $next($request);
}
}
