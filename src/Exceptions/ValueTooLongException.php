<?php

namespace Kyivstar\Api\Exceptions;

class ValueTooLongException extends ValueException
{
    public function __construct(?string $value, int $max, string $in)
    {
        parent::__construct("maximum $max length", strlen($value), $in);
    }
}
