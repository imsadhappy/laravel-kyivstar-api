<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Traits\HttpValidator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\PendingRequest;

abstract class JsonHttpService
{
    use HttpValidator;

    protected string $url;

    private $authentication;

    private PendingRequest $request;

    protected function __construct(callable $authentication)
    {
        $this->authentication = $authentication;

        $this->setupRequest();
    }

    private function setupRequest(bool $forceRefresh = false): PendingRequest
    {
        list($type, $token) = ($this->authentication)($forceRefresh);
        $this->request = Http::withToken($token, ucfirst($type))->asJson();

        return $this->request;
    }

    /**
     * Call get|post|put with retry if stale token
     *
     * @param string $method
     * @param string|null $endpoint
     * @param array|null $payload
     * @return Response
     */
    protected function try(string $method, ?string $endpoint = '', array $payload = null): Response
    {
        $response = $this->request->{$method}($this->url . $endpoint, $payload);

        if ($response->status() === 401) { //one more try with forceRefresh of accessToken
            $response = $this->setupRequest(true)->{$method}($this->url . $endpoint, $payload);
        }

        return $this->is200($response, fn() => $response);
    }
}
