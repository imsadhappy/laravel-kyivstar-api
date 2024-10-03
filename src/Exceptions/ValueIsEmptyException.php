<?php

namespace Kyivstar\Api\Exceptions;

class ValueIsEmptyException extends ValueException
{
    public function __construct(?string $value, string $in)
    {
        parent::__construct("not empty", strlen($value), $in);
    }
}