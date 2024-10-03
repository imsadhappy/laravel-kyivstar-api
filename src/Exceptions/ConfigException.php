<?php

namespace Kyivstar\Api\Exceptions;

class ConfigException extends \Exception
{
    const MISSING_CONFIG = 1;

    const EMPTY_VALUE = 2;

    const INVALID_VERSION = 3;

    const INVALID_SERVER = 4;

    public function __construct(string $message, int $code = 0)
    {
        parent::__construct("Invalid config: $message", $code);
    }
}
