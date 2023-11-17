<?php

namespace Tests\Feature\Player;

use App\Models\Player;
use App\Traits\JwtGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatchTest extends TestCase
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
     * testUpdateSuccess
     * 更新成功
     * @test
     * @group patchApi
     * @return void
     */
    public function testUpdateSuccess()
    {
        $iPlayerId = Player::max('id');

        $aData = [
            'displayName' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'status' => 'active',
            'balance' => 100,
        ];

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->patch("/api/players/{$iPlayerId}", $aData);

        $oResponse->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('Player', [
            'id' => $iPlayerId,
            'displayName' => $aData['displayName'],
            'email' => $aData['email'],
            'status' => $aData['status'],
            'balance' => $aData['balance'],
        ]);
    }

    /**
     * testUpdateInvalidId
     * 更新失敗 無此id
     * @test
     * @group patchApi
     * @return void
     */
    public function testUpdateInvalidId()
    {
        $iInvalidId = 0;

        $aData = [
            'displayName' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'status' => 'inactive',
            'balance' => 100,
        ];

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->patch("/api/players/{$iInvalidId}", $aData);

        $oResponse->assertStatus(400)
            ->assertJson(['status' => 'failure',
                'error' => [
                    'code' => 400,
                    'message' => 'Player not found',
                ],
            ]);
    }

    /**
     * testUpdateInvalidJwt
     * 更新失敗 無效的Jwt
     * @test
     * @group patchApi
     * @return void
     */
    public function testUpdateInvalidJwt()
    {
        $oPlayer = Player::first();

        $aData = [
            'displayName' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'status' => 'active',
            'balance' => 100,
        ];

        $sInvalidJwt = 'invalid_jwt';

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $sInvalidJwt"])
            ->patch("/api/players/{$oPlayer->id}", $aData);

        $oResponse->assertStatus(401)
            ->assertJson([
                'status' => 'failure',
                'error' => [
                    'code' => 401,
                    'message' => 'Invalid JWT or expired token',
                ],
            ]);
    }

    /**
     * testUpdateAccountExists
     * 更新失敗 帳號重複
     * @test
     * @group patchApi
     * @return void
     */
    public function testUpdateAccountExists()
    {
        $iPlayerId = Player::first()->id;

        $aData = [
            'account' => 'test',
            'displayName' => 'test123',
        ];

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->patch("/api/players/{$iPlayerId}", $aData);

        $oResponse->assertStatus(400)
            ->assertJson([
                'status' => 'failure',
                'error' => [
                    'message' => 'The account has already been taken.',
                ],
            ]);
    }
}
