<?php

namespace Tests\Feature\Auth;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('PlayerSeeder');
    }

    /**
     * testLoginSuccess
     * 登入成功
     * @test
     * @group authApi
     */
    public function testLoginSuccess()
    {
        $oResponse = $this->post('/api/auth/login', [
            'account' => 'test',
            'password' => '123456789',
        ]);

        $oResponse->assertStatus(200);
    }

    /**
     * testLoginFailureInvalidPassword
     * 登入失敗 - 密碼錯誤
     * @test
     * @group authApi
     */
    public function testLoginFailureInvalidPassword()
    {
        $oResponse = $this->post('/api/auth/login', [
            'account' => 'test',
            'password' => 'invalid_password',
        ]);

        $oResponse->assertStatus(401)
            ->assertJson([
                'status' => 'failure',
                'error' => [
                    'code' => 401,
                    'message' => 'Invalid credentials',
                ],
            ]);
    }

    /**
     * testLoginFailureInactiveAccount
     * 登入失敗 - 非active帳號
     * @test
     * @group authApi
     */
    public function testLoginFailureInactiveAccount()
    {
        $sUniqueAccount = $this->faker->unique()->userName;
        $sValidAccount = preg_replace('/[^A-Za-z0-9_-]/', '_', $sUniqueAccount);

        $oPlayer = Player::create([
            'account' => $sValidAccount,
            'displayName' => $this->faker->name,
            'password' => password_hash('123456789', PASSWORD_DEFAULT),
            'email' => $this->faker->unique()->safeEmail,
            'status' => 'inactive',
            'balance' => 0,
            'lastLoggedInIp' => '127.0.0.1',
            'lastLoggedInAt' => now(),
        ]);

        $oResponse = $this->post('/api/auth/login', [
            'account' => $oPlayer->account,
            'password' => '123456789',
        ]);

        $oResponse->assertStatus(403)
            ->assertJson([
                'status' => 'failure',
                'error' => [
                    'code' => 403,
                    'message' => 'Inactive player',
                ],
            ]);
    }

}
