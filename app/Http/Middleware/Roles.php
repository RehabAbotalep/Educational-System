<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class Roles
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $userRole = auth()->user()->role->name;
        $allowedRoles = explode('.', $roles);

        if( ! in_array($userRole, $allowedRoles)){
            return $this->apiResponse(422, 'Not allowed roles');
        }

        return $next($request);
    }
}
