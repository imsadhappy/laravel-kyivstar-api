<?php declare(strict_types=1);

namespace Kyivstar\Api\Dto;

use Kyivstar\Api\Traits\ObjectToArray;
use Kyivstar\Api\Traits\ValueValidator;

abstract class Message
{
    use ObjectToArray, ValueValidator;

    const MIN_TTL = 0;

    const MAX_TTL = 86400;

    public string $to;

    public string $from;
    
    public string $text;

    public int $messageTtlSec;

    /**
     * @param string $from
     * @param string $to
     * @param string $text
     * @param int|null $messageTtlSec
     */
    public function __construct(string $from,
                                string $to,
                                string $text,
                                ?int   $messageTtlSec = null)
    {
        $this->to = $this->minLength($to, 9);
        $this->from = $this->notEmpty($from);
        $this->text = $this->notEmpty($text);

        if (!is_null($messageTtlSec)) {
            $this->messageTtlSec = $this->between($messageTtlSec, self::MIN_TTL, self::MAX_TTL);
        }
    }
}
