<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsEmployeeOrAdmin
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && ($user->role === 'employee' || $user->role === 'admin')) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses ditolak.');
    }
}
