<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->is_suspended) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['account' => 'This account has been suspended by administration.']);
        }

        if (empty($roles)) {
            $roles = ['super_admin', 'admin'];
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized. Administrative credentials required to access this sanctuary.');
        }

        return $next($request);
    }
}
