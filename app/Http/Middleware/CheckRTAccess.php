<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRTAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Jika belum login, redirect ke login
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Admin RW bisa akses semua
        if ($user->isAdminRW()) {
            return $next($request);
        }
        
        // Admin RT - akan dicheck di controller level untuk setiap resource
        // Middleware ini hanya memastikan user sudah login dan punya role yang valid
        if ($user->isAdminRT()) {
            return $next($request);
        }
        
        // Jika tidak ada role yang valid, redirect dengan error
        return redirect()->route('home')
            ->with('error', 'Anda tidak memiliki akses yang valid.');
    }
}
