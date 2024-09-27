<?php

namespace Kyivstar\Api;

use Illuminate\Support\Facades\Facade;

class KyivstarApiFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return KyivstarApi::class;
    }
}