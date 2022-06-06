<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use App\Traits\JsonResponse;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    use JsonResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //$user = JWTAuth::authenticate($request->bearerToken());
        //if (!$user) return $this->error(401, 'Authorization Token not found');
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $payload = \Tymon\JWTAuth\Facades\JWTAuth::getPayload($request->bearerToken());
            JWTAuth::getBlacklist()->has($payload);
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return $this->error(401, 'Token is Invalid');
            } else if ($e instanceof TokenExpiredException) {
                return $this->error(401, 'Token is Expired');
            } else if ($e instanceof TokenBlacklistedException) {
                return $this->error(401, 'Token is Blacklist');
            } else {
                return $this->error(401, 'Authorization Token not found');
            }
        }
        return $next($request);
    }
}
