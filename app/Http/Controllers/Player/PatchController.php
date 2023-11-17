<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePlayerRequest;
use App\Models\Player;

class PatchController extends Controller
{
    public function update(UpdatePlayerRequest $oRequest, $iId)
    {
        $oPlayer = Player::find($iId);

        if (!$oPlayer) {
            throw new \Exception('Player not found', 400);
        }

        $oPlayer->fill($oRequest->all());

        if (!$oPlayer->save()) {
            throw new \Exception('Failed to update player', 500);
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

}
