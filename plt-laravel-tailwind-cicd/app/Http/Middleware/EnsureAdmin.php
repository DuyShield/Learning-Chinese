<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Chỉ quản trị viên mới được vào khu vực này.');
        }
        return $next($request);
    }
}
