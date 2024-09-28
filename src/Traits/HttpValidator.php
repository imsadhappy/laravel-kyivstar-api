<?php

namespace Kyivstar\Api\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;
use Kyivstar\Api\Exceptions\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

trait HttpValidator {

    /**
     * @param Response $response
     * @param callable $callback
     * @return mixed
     * @throws AuthenticationException|UnprocessableEntityHttpException|RequestException
     */
    public function is200(Response $response, callable $callback)
    {
       switch ($response->status()) {
            case 200:
                return $callback($response);
            case 401:
                throw new AuthenticationException($response->json('error_verbose')); 
            case 422:
                throw new UnprocessableEntityHttpException($response->json('errorMsg'));
            default:
                throw $response->toException();
        }
    }
}
