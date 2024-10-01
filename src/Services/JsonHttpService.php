<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Traits\HttpValidator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\PendingRequest;

abstract class JsonHttpService
{
    use HttpValidator;

    protected string $baseUrl = 'https://api-gateway.kyivstar.ua/';

    protected string $url;

    private $authentication;

    /**
     * @param string $endpoint
     * @param string $server
     * @param string $version
     * @param callable $authentication
     */
    protected function __construct(string $endpoint,
                                   string $server,
                                   string $version,
                                   callable $authentication)
    {
        $this->url = $this->baseUrl . ($server === 'production' ? '' : $server) . "/rest/$version/$endpoint";
        $this->authentication = $authentication;
    }

    protected function get(string $endpoint = '', $query = null): Response
    {
        return $this->request('GET', $endpoint, $query);
    }

    protected function post(array $payload = [], ?string $endpoint = ''): Response
    {
        return $this->request('POST', $endpoint, $payload);
    }

    protected function put(array $payload = [], ?string $endpoint = ''): Response
    {
        return $this->request('PUT', $endpoint, $payload);
    }

    /**
     * Call get|post|put with retry if stale token
     *
     * @param string $method
     * @param string|null $endpoint
     * @param array|null $payload
     * @return Response
     */
    private function request(string $method, 
                             ?string $endpoint = '', 
                             ?array $payload = []): Response
    {
        $request = function ($authentication, $forceRefresh = false) use ($method, $endpoint, $payload): Response {

            list($tokenType, $token) = $authentication($forceRefresh);

            return Http::withToken($token, ucfirst($tokenType))->asJson()->{$method}($this->url . $endpoint, $payload);
        };

        $response = $request($this->authentication);

        if ($response->status() === 401) { //one more try with forceRefresh of accessToken
            $response = $request($this->authentication, true);
        }

        return $this->is200($response, fn() => $response);
    }
}
