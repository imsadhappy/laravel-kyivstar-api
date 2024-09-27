<?php

namespace Kyivstar\Api\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class HttpService
{
    protected string $url;

    private $authentication;

    private array $headers;

    private PendingRequest $request;

    protected function __construct(callable $authentication,
                                   array $headers = ['Accept' => 'application/json',
                                                     'Content-Type' => 'application/json'])
    {
        $this->authentication = $authentication;
        $this->headers = $headers;

        $this->setupRequest();
    }

    private function setupRequest(bool $forceRefresh = false): PendingRequest
    {
        $this->request = Http::withHeaders($this->headers)->withToken(($this->authentication)($forceRefresh));

        return $this->request;
    }

    /**
     * Call get|post|put with retry if stale token
     *
     * @param string $method
     * @param string|null $endpoint
     * @param iterable|null $payload
     * @return Response
     * @throws RequestException
     */
    protected function try(string $method, ?string $endpoint = '', ?iterable $payload = []): Response
    {
        /** @var Response $response */
        $response = $this->request->{$method}($this->url . $endpoint, $payload);

        if ($response->status() === 401){
            $response = $this->setupRequest(true)->{$method}($this->url . $endpoint, $payload);
        }

        if ($response->status() === 200) {
            return $response;
        }

        throw $response->toException();
    }
}