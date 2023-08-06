<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = preg_replace('/^Bearer\s+(.*?)$/', '$1', $request->header('Authorization'));

        if (! empty($token) && $token === config('auth.check_auth_token')) {
            return $next($request);
        } else {
            throw new AccessDeniedException('you have not permission');
        }
    }
}
