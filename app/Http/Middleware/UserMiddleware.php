<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_active) {
            return redirect()->route('login')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้หรือบัญชีของคุณถูกระงับ');
        }

        return $next($request);
    }
}