<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Player;

class GetController extends Controller
{
    public function index()
    {
        $oPlayers = Player::all();

        return response()->json([
            'status' => 'success',
            'data' => $oPlayers,
        ]);
    }

    public function show($iId)
    {
        $oPlayer = Player::find($iId);

        if (!$oPlayer) {
            throw new \Exception('Player not found', 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $oPlayer,
        ]);
    }

}
