<?php

namespace App\Http\Middleware;

use App\Helpers\Constants\StatusEnum;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IsActiveUser
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
        if (auth()->user()->status === StatusEnum::ACTIVE->value) {
            return $next($request);
        }

        abort(403, 'You are banned.');
    }
}
