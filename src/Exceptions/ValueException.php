<?php

namespace Kyivstar\Api\Exceptions;

class ValueException extends \Exception
{
    public function __construct(string $message, ?string $value, string $in)
    {
        $value = htmlentities($value);
        
        parent::__construct("Value must be $message, got '$value' in '$in'");
    }
}
