<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Traits\HttpValidator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

abstract class JsonHttpService
{
    use HttpValidator;

    protected string $url;

    private $authentication;

    /**
     * @param string $base
     * @param string $server
     * @param string $version
     * @param callable $authentication
     */
    protected function __construct(string $base,
                                   string $server,
                                   string $version,
                                   callable $authentication)
    {
        /**
         * Maybe in other versions (not v1beta)
         * other endpoint URLs will be used.
         * So we can add switch (config()->get("kyivstar-api.version")) here later.
         */
        $server = $server === 'production' ? '/' : "/$server/";
        $this->url = "https://api-gateway.kyivstar.ua{$server}rest/{$version}/{$base}";
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

        return $this->is200($response);
    }
}
