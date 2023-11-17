<?php

namespace Tests\Feature\Auth;

use App\Models\Player;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Traits\JwtGenerator;

class LogoutTest extends TestCase
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
     * testLogoutSuccess
     * 登出成功
     * @test
     * @group logoutApi
     * @return void
     */
    public function testLogoutSuccess()
    {
        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->post('/api/auth/logout');

        $oResponse->assertStatus(200)
            ->assertJson(['status' => 'success', 'data' => ['message' => 'Logged out successfully']]);

    }

    /**
     * testLogoutInvalidJwt
     * 登出失敗 無效的Jwt
     * @test
     * @group logoutApi
     * @return void
     */
    public function testLogoutInvalidJwt()
    {
        $sInvalidJwt = 'invalid_jwt';

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $sInvalidJwt"])
            ->post('/api/auth/logout');

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
