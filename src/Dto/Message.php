<?php

namespace Kyivstar\Api\Dto;

use Kyivstar\Api\Traits\PropsIterator;
use Kyivstar\Api\Traits\ValueValidator;
use Kyivstar\Api\Exceptions\ValueException;

abstract class Message implements \Iterator
{
    use ValueValidator, PropsIterator;

    /**
     * @param string $from
     * @param string $to
     * @param string $text
     * @throws ValueException
     */
    public function __construct(string $from,
                                string $to,
                                string $text)
    {
        $this->props = [
            'to' => $this->minLength($to, 9),
            'from' => $this->notEmpty($from),
            'text' => $this->notEmpty($text)
        ];
    }
}