<?php

namespace Tests\Feature\Player;

use App\Models\Player;
use App\Traits\JwtGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTest extends TestCase
{
    use RefreshDatabase;
    use JwtGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('PlayerSeeder');

        $this->sJwt = $this->generateJwt()['token'];
    }

    /**
     * testIndexSuccess
     * 查詢成功
     * @test
     * @group getApi
     * @return void
     */
    public function testIndexSuccess()
    {
        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->get('/api/players');

        $oResponse->assertStatus(200);

    }

    /**
     * testIndexWithoutJwt
     * 查詢失敗 沒有Jwt
     * @test
     * @group getApi
     * @return void
     */
    public function testIndexWithoutJwt()
    {
        $oResponse = $this->get('/api/players');

        $oResponse->assertStatus(400)
            ->assertJson([
                'status' => 'failure',
                'error' => [
                    'code' => 400,
                    'message' => 'JWT not provided',
                ],
            ]);
    }

    /**
     * testIndexInvalidJwt
     * 查詢失敗 無效的Jwt
     * @test
     * @group getApi
     * @return void
     */
    public function testIndexInvalidJwt()
    {
        $sJwt = 'invalid_jwt';

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $sJwt"])
            ->get('/api/players');

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
     * testShow
     * 查詢成功
     * @test
     * @group getApi
     * @return void
     */
    public function testShowSuccess()
    {
        $iPlayerId = Player::first()->id;

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->get("/api/players/{$iPlayerId}");

        $oResponse->assertStatus(200);
    }

    /**
     * testShowInvalidId
     * 查詢失敗 無此id
     * @test
     * @group getApi
     * @return void
     */
    public function testShowInvalidId()
    {
        $iPlayerId = 0;

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->get("/api/players/{$iPlayerId}");

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
     * testShowInvalidJwt
     * 查詢失敗 無效的Jwt
     * @test
     * @group getApi
     * @return void
     */
    public function testShowInvalidJwt()
    {
        $sJwt = 'invalid_jwt';

        $iPlayerId = Player::first()->id;

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $sJwt"])
            ->get("/api/players/{$iPlayerId}");

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
