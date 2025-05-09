<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ( (!isset($user->userpackage->active) || $user->userpackage->active == 0) || (empty($user->userpackage->end) || date('Y-m-d', strtotime($user->userpackage->end)) < date('Y-m-d')) ) {
            return redirect('/');
        }

        return $next($request);
    }
}
