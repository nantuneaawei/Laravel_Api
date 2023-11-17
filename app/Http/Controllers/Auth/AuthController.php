<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAuthRequest;
use App\Models\Player;
use App\Traits\JwtGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    use JwtGenerator;

    public function login(LoginAuthRequest $oRequest)
    {
        $oPlayer = Player::where('account', $oRequest->input('account'))->first();

        if (!$oPlayer || !password_verify($oRequest->input('password'), $oPlayer->password)) {
            throw new \Exception('Invalid credentials', 401);
        }

        if ($oPlayer->status !== 'active') {
            throw new \Exception('Inactive player', 403);
        }

        $aJwt = $this->generateJwt();

        $this->storePlayerDataInRedis($aJwt['jti'], $oPlayer);

        return response()->json([
            'status' => 'success',
            'data' => ['jwt' => $aJwt['token']],
        ]);
    }

    public function logout(Request $oRequest)
    {
        $sJti = $oRequest->attributes->get('decodedJwt')->jti;

        Redis::del($sJti);

        return response()->json([
            'status' => 'success',
            'data' => ['message' => 'Logged out successfully'],
        ]);
    }

    protected function storePlayerDataInRedis($oJti, $oPlayer)
    {
        $aPlayerData = [
            'account' => $oPlayer->account,
            'displayName' => $oPlayer->displayName,
            'balance' => $oPlayer->balance,
        ];

        $sPlayerJson = json_encode($aPlayerData);

        Redis::set($oJti, $sPlayerJson, 'EX', 3600);

    }
}
