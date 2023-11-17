<?php

namespace Tests\Unit\MiddlewareTests;

use App\Http\Middleware\JwtMiddleware;
use App\Traits\JwtGenerator;
use Illuminate\Http\Request;
use Tests\TestCase;

class JwtMiddlewareTest extends TestCase
{
    use JwtGenerator;

    protected $oRequest;
    protected $oMiddleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sJwt = $this->generateJwt()['token'];

        $this->oRequest = Request::create('/api/some-route', 'GET');

        $this->oMiddleware = new JwtMiddleware();
    }

    /**
     * testHandling
     *
     * @test
     * @group JwtMiddleware
     * @return void
     */
    public function testHandling()
    {
        $this->oRequest->headers->set('Authorization', "Bearer $this->sJwt");

        $oResponse = $this->oMiddleware->handle($this->oRequest, function () {
            $this->assertTrue(true);
        });

    }

    /**
     * testHandlingInvalidJwt
     *
     * @test
     * @group JwtMiddleware
     * @return void
     */
    public function testHandlingInvalidJwt()
    {
        $sJwt = 'invalid_jwt';

        $this->oRequest->headers->set('Authorization', "Bearer $sJwt");

        $exceptionThrown = false;

        try {
            $oResponse = $this->oMiddleware->handle($this->oRequest, function () {
            });
        } catch (\Exception $e) {
            $exceptionThrown = true;
            $this->assertEquals('Invalid JWT or expired token', $e->getMessage());
            $this->assertEquals(401, $e->getCode());
        }

        $this->assertTrue($exceptionThrown);
    }

    /**
     * testHandlingWithoutId
     *
     * @test
     * @group JwtMiddleware
     * @return void
     */
    public function testHandlingWithoutId()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('JWT not provided');
        $this->expectExceptionCode(400);

        $oResponse = $this->oMiddleware->handle($this->oRequest, function () {

        });
    }
}
