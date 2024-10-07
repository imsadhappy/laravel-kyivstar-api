<?php

namespace Kyivstar\Api\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;
use Kyivstar\Api\Exceptions\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

trait HttpValidator {

    /**
     * @param Response $response
     * @param callable|null $callback
     * @return mixed
     * @throws AuthenticationException|UnprocessableEntityHttpException|RequestException
     */
    public function is200(Response $response, ?callable $callback = null)
    {
       switch ($response->status()) {
            case 200:
                return is_callable($callback) ? $callback($response) : $response;
            case 401:
                throw new AuthenticationException($response->json('error.message') ?? 'Unauthorized');
            case 404:
                throw new NotFoundHttpException($response->json('errorMsg') ?? 'Not Found');
            case 422:
                throw new UnprocessableEntityHttpException($response->json('errorMsg') ?? 'Unprocessable Entity');
            default:
                throw $response->toException();
        }
    }
}
