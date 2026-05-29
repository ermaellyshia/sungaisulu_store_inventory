<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = $request->user();

        if (! $user || ! $this->userHasRole($user->role, $roles)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    private function userHasRole(?string $userRole, string $roles): bool
    {
        if (! $userRole) {
            return false;
        }

        $allowedRoles = array_map('trim', preg_split('/[|,]/', $roles, -1, PREG_SPLIT_NO_EMPTY));

        return in_array($userRole, $allowedRoles, true);
    }
}
