<?php

namespace Core\Infrastructure\Adapters\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();

        if (!$user || $user->role !== $role) {
            return response()->json(['error' => 'Acesso não autorizado'], 403);
        }

        return $next($request);
    }
}
