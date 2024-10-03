<?php

namespace Kyivstar\Api\Exceptions;

class ValueNotUrlException extends ValueException
{
    public function __construct(?string $value, string $in)
    {
        parent::__construct("not valid URL", $value, $in);
    }
}
