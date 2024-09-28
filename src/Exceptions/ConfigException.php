<?php

namespace Kyivstar\Api\Exceptions;

use Exception;

class ConfigException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Invalid config: $message");
    }
}
