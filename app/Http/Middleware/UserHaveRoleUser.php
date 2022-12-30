<?php

namespace App\Http\Middleware;

use App\Helpers\Constants\RolesEnum;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserHaveRoleUser
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $role = auth()->user()->role;

        if ($role === RolesEnum::USER->value || $role === RolesEnum::ADMIN->value) {
            return $next($request);
        }

        redirect()->route('login');
    }
}
