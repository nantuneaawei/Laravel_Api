<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Player;

class DeleteController extends Controller
{
    public function destroy($iId)
    {
        $oPlayer = Player::find($iId);

        if (!$oPlayer) {
            throw new \Exception('Player not found', 400);
        }

        try {
            $oPlayer->delete();
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete player', 500, $e);
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

}
