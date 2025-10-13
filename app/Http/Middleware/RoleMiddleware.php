<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = auth()->user();
        
        // Cek apakah user memiliki role yang diizinkan
        $userRole = strtolower(trim($user->role ?? ''));
        
        // Normalisasi role yang diizinkan
        $allowedRoles = array_map(function($role) {
            return strtolower(trim($role));
        }, $roles);

        // Cek apakah user role ada dalam daftar yang diizinkan
        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        // Jika tidak memiliki akses
        return back()->with('error', 'Anda tidak memiliki akses untuk melakukan tindakan ini. Hanya Super Admin yang diizinkan.');
    }
}