<?php

namespace Tests\Feature\Player;

use App\Models\Player;
use App\Traits\JwtGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use JwtGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('PlayerSeeder');

        $this->sJwt = $this->generateJwt()['token'];
    }

    /**
     * testDestroySuccess
     * 刪除成功
     * @test
     * @group deleteApi
     * @return void
     */
    public function testDestroySuccess()
    {
        $iPlayerId = Player::max('id');

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->delete("/api/players/{$iPlayerId}");

        $oResponse->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertTrue(Player::where('id', $iPlayerId)->doesntExist());
    }

    /**
     * testDestroyInvalidId
     * 刪除失敗 玩家不存在
     * @test
     * @group deleteApi
     * @return void
     */
    public function testDestroyInvalidId()
    {
        $iPlayerId = 0;

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->delete("/api/players/{$iPlayerId}");

        $oResponse->assertStatus(400)
            ->assertJson([
                'status' => 'failure',
                'error' => [
                    'code' => 400,
                    'message' => 'Player not found',
                ],
            ]);
    }

    /**
     * testDestroyInvalidJwt
     * 刪除失敗 無效的jwt
     * @test
     * @group deleteApi
     * @return void
     */
    public function testDestroyInvalidJwt()
    {
        $iPlayerId = Player::first()->id;

        $sJwt = 'invalid_jwt';

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $sJwt"])
            ->delete("/api/players/{$iPlayerId}");

        $oResponse->assertStatus(401)
            ->assertJson([
                'status' => 'failure',
                'error' => [
                    'code' => 401,
                    'message' => 'Invalid JWT or expired token',
                ],
            ]);
    }

}
