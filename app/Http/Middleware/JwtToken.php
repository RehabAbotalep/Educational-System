<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtToken
{   use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        try{
            JWTAuth::parseToken()->authenticate();
        }catch(\Exception $e){
            if($e instanceof TokenExpiredException){
                return $this->apiResponse(422, 'Token is expired');
            }elseif($e instanceof TokenInvalidException){
                return $this->apiResponse(422, 'Token is invalid');
            }else{
                return $this->apiResponse(422, 'Token not found');
            }
        }


        return $next($request);
    }
}
