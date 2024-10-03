<?php

namespace Kyivstar\Api\Tests\Unit;

use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Traits\HttpValidator;
use Illuminate\Support\Facades\Http;
use Kyivstar\Api\Exceptions\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class HttpValidatorTest extends TestCase
{
    private $mock;

    private string $validResponse;


    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = new class {
            use HttpValidator;
        };

        $this->validResponse = fake()->sentence();

        Http::fake([
            'https://site200.com' => Http::response($this->validResponse, 200),
            'https://site401.com' => Http::response(['error_verbose' => fake()->sentence()],401),
            'https://site422.com' => Http::response(['errorMsg' => fake()->sentence()],422),
        ]);
    }

    public function testHttp200()
    {
        $responseBody = $this->mock->is200(Http::get('https://site200.com'),
                                           fn($response) => $response->getBody()->getContents());

        $this->assertEquals($this->validResponse, $responseBody);
    }

    public function testHttp401()
    {
        $this->expectException(AuthenticationException::class);

        $this->mock->is200(Http::get('https://site401.com'));
    }

    public function testHttp422()
    {
        $this->expectException(UnprocessableEntityHttpException::class);

        $this->mock->is200(Http::get('https://site422.com'));
    }
}