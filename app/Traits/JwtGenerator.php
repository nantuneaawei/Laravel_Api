<?php

namespace App\Traits;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;

trait JwtGenerator
{
    protected function generateJwt()
    {
        $oJti = Str::uuid();
        $iIssueTime = Carbon::now()->timestamp;
        $iExpirationTime = Carbon::now()->addHour()->timestamp;

        $aPayload = [
            'jti' => $oJti,
            'iat' => $iIssueTime,
            'nbf' => $iIssueTime,
            'exp' => $iExpirationTime,
        ];

        $sToken = JWT::encode($aPayload, env('JWT_SECRET'), 'HS256');

        return ['jti' => $aPayload['jti'], 'token' => $sToken];
    }
}
