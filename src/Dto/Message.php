<?php declare(strict_types=1);

namespace Kyivstar\Api\Dto;

use Kyivstar\Api\Traits\ObjectToArray;
use Kyivstar\Api\Traits\ValueValidator;
use Kyivstar\Api\Exceptions\ValueException;

abstract class Message
{
    use ObjectToArray, ValueValidator;

    public string $to;

    public string $from;
    
    public string $text;

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
        $this->to = $this->minLength($to, 9);
        $this->from = $this->notEmpty($from);
        $this->text = $this->notEmpty($text);
    }
}
