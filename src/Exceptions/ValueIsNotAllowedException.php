<?php

namespace Kyivstar\Api\Exceptions;

class ValueIsNotAllowedException extends ValueException
{
    public function __construct(string $value, array $allowedOptions, string $in)
    {
        parent::__construct("is not one of allowed options - ".join('|', $allowedOptions), $value, $in);
    }
}
