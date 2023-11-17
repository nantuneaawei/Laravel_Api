<?php

namespace Tests\Feature\Player;

use App\Traits\JwtGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use JwtGenerator;

    protected $aData;
    protected $sValidAccount;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('PlayerSeeder');

        $this->sJwt = $this->generateJwt()['token'];

        $sUniqueAccount = $this->faker->unique()->userName;
        $this->sValidAccount = preg_replace('/[^A-Za-z0-9_-]/', '_', $sUniqueAccount);

        $this->aData = [
            'account' => $this->sValidAccount,
            'displayName' => $this->faker->name,
            'password' => $this->faker->password,
            'email' => $this->faker->unique()->safeEmail,
            'status' => 'active',
            'balance' => 0,
        ];
    }

    public function storeFailureData()
    {
        return [
            // 缺少 'balance'
            [
                [
                    'account' => 'test123',
                    'displayName' => 'Test User',
                    'password' => 'password123',
                    'email' => 'test@example.com',
                    'status' => 'active',
                ],
                'The balance field is required.',
            ],

            // 帳號長度不符合
            [
                [
                    'account' => '123',
                    'displayName' => 'Test User',
                    'password' => 'password123',
                    'email' => 'test@example.com',
                    'status' => 'active',
                    'balance' => 0,
                ],
                'Warning during account creation.',
            ],
        ];
    }

    /**
     * testStoreSuccess
     * 新增成功
     * @test
     * @group postApi
     * @return void
     */
    public function testStoreSuccess()
    {
        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->post('/api/players', $this->aData);

        $oResponse->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('Player', ['account' => $this->sValidAccount]);
    }

    /**
     * testStoreFailure
     * 新增失敗 資料輸入不完整
     *
     * @test
     * @dataProvider storeFailureData
     * @group postApi
     * @param array $inputData
     * @param string $expectedErrorMessages
     */
    public function testStoreFailure(array $inputData, string $expectedErrorMessage)
    {
        $oResponse = $this->withHeaders(['Authorization' => "Bearer $this->sJwt"])
            ->post('/api/players', $inputData);

        $oResponse->assertStatus(400)
            ->assertJson([
                'status' => 'failure',
                'error' => [
                    'message' => $expectedErrorMessage,
                ],
            ]);
    }

    /**
     * testStoreInvalidJwt
     * 新增失敗 無效的jwt
     * @test
     * @group postApi
     * @return void
     */
    public function testStoreInvalidJwt()
    {
        $sJwt = 'invalid_jwt';

        $oResponse = $this->withHeaders(['Authorization' => "Bearer $sJwt"])
            ->post('/api/players', $this->aData);

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
