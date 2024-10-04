<?php

namespace Kyivstar\Api\Tests\Unit;

use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Traits\HttpValidator;
use Illuminate\Support\Facades\Http;
use Kyivstar\Api\Exceptions\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class HttpValidatorTest extends TestCase
{
    private $mock;

    private string $responseText = 'not important';


    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = new class {
            use HttpValidator;
        };

        Http::fake([
            'https://site200.com' => Http::response($this->responseText, 200),
            'https://site401.com' => Http::response(['error_verbose' => $this->responseText],401),
            'https://site404.com' => Http::response(['errorMsg' => $this->responseText],404),
            'https://site422.com' => Http::response(['errorMsg' => $this->responseText],422),
        ]);
    }

    public function testHttp200()
    {
        $responseText = $this->mock->is200(Http::get('https://site200.com'),
                                           fn($response) => $response->getBody()->getContents());

        $this->assertEquals($this->responseText, $responseText);
    }

    public function testHttp401()
    {
        $this->expectException(AuthenticationException::class);

        $this->mock->is200(Http::get('https://site401.com'));
    }

    public function testHttp404()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->mock->is200(Http::get('https://site404.com'));
    }

    public function testHttp422()
    {
        $this->expectException(UnprocessableEntityHttpException::class);

        $this->mock->is200(Http::get('https://site422.com'));
    }
}