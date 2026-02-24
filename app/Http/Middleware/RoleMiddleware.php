<?php

// Lokasi: app/Http/Middleware/RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Jika user belum login atau role-nya tidak sesuai, tolak aksesnya!
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'MAAF, ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        }

        return $next($request);
    }
}
