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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has the 'Admin' role
        if (auth()->check() && auth()->user()->role === 'Admin') {
            return $next($request);
        }

        // If the user is not an admin, redirect to the home page with an error message
        return redirect('/')->with('error', 'You do not have admin access.');
    }
}
