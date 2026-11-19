<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class EnforceAbsoluteSessionLifetime
{
    private const LOGIN_TIMESTAMP_KEY = 'auth.login_at';

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            $session = $request->session();
            $loginAt = $session->get(self::LOGIN_TIMESTAMP_KEY);
            $now = now()->timestamp;

            if ($loginAt === null) {
                $session->put(self::LOGIN_TIMESTAMP_KEY, $now);
            } elseif (
                ! is_numeric($loginAt)
                || (int) $loginAt > $now
                || $now - (int) $loginAt >= (int) config('session.absolute_lifetime') * 60
            ) {
                Auth::guard('web')->logout();
                $session->invalidate();
                $session->regenerateToken();
                Cookie::queue(
                    Cookie::forget(
                        config('session.cookie'),
                        config('session.path'),
                        config('session.domain')
                    )
                );

                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
