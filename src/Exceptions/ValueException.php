<?php

namespace Kyivstar\Api\Exceptions;

use Exception;

class ValueException extends Exception
{
    public function __construct(string $message, string $value, string $in)
    {
        parent::__construct("Value must be $message, got $value in '$in'");
    }
}