<?php

namespace Kyivstar\Api\Exceptions;

class ValueNotBetweenException extends ValueException
{
    public function __construct($value, $min, $max, string $in)
    {
        parent::__construct("between $min and $max", floatval($value), $in);
    }
}