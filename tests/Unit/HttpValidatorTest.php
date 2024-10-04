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

    private string $url;

    private string $responseText;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = fake()->url();
        $this->responseText = fake()->text();
        $this->mock = new class {
            use HttpValidator;
        };

        Http::fake([
            "{$this->url}/200" => Http::response($this->responseText, 200),
            "{$this->url}/401" => Http::response(['error_verbose' => $this->responseText],401),
            "{$this->url}/404" => Http::response(['errorMsg' => $this->responseText],404),
            "{$this->url}/422" => Http::response(['errorMsg' => $this->responseText],422),
        ]);
    }

    public function testHttp200()
    {
        $responseText = $this->mock->is200(Http::get("{$this->url}/200"),
                                           fn($response) => $response->getBody()->getContents());

        $this->assertEquals($this->responseText, $responseText);
    }

    public function testHttp401()
    {
        $this->expectException(AuthenticationException::class);

        $this->mock->is200(Http::get("{$this->url}/401"));
    }

    public function testHttp404()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->mock->is200(Http::get("{$this->url}/404"));
    }

    public function testHttp422()
    {
        $this->expectException(UnprocessableEntityHttpException::class);

        $this->mock->is200(Http::get("{$this->url}/422"));
    }
}