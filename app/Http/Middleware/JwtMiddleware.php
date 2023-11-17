<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $oRequest, Closure $oNext)
    {
        $sToken = $oRequest->bearerToken();

        if (!$sToken) {
            throw new \Exception('JWT not provided', 400);
        }

        try {
            $oDecodedJwt = JWT::decode($sToken, new Key(env('JWT_SECRET'), 'HS256'));
            $oRequest->attributes->set('decodedJwt', $oDecodedJwt);
        } catch (\Exception $e) {
            throw new \Exception('Invalid JWT or expired token', 401);
        }

        return $oNext($oRequest);
    }
}
