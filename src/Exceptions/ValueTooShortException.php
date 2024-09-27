<?php

namespace Kyivstar\Api\Exceptions;

class ValueTooShortException extends ValueException
{
    public function __construct(string $value, int $min, string $in)
    {
        parent::__construct("minimum $min length", strlen($value), $in);
    }
}