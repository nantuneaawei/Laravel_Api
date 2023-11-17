<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePlayerRequest;
use App\Models\Player;
use Carbon\Carbon;

class PostController extends Controller
{
    public function store(CreatePlayerRequest $oRequest)
    {
        $sClientIp = $oRequest->ip();

        $oCurrentTime = Carbon::now();

        $aData = $oRequest->validated();

        $aData['password'] = password_hash($aData['password'], PASSWORD_DEFAULT);
        $aData['lastLoggedInIp'] = $sClientIp;
        $aData['lastLoggedInAt'] = $oCurrentTime;

        Player::create($aData);

        return response()->json([
            'status' => 'success',
        ]);
    }

}
