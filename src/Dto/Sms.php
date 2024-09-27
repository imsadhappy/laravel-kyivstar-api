<?php declare(strict_types=1);

namespace Kyivstar\Api\Dto;

use Kyivstar\Api\Exceptions\ValueException;

class Sms extends Message
{
    /**
     * @param string $from
     * @param string $to
     * @param string $text
     * @param int|null $maxSegments
     * @param int|null $messageTtlSec
     * @throws ValueException
     */
    public function __construct(string $from,
                                string $to,
                                string $text,
                                ?int   $maxSegments = null,
                                ?int   $messageTtlSec = null)
    {
        parent::__construct($from, $to, $text);

        if (is_null($maxSegments) && strlen($text) > 70) {
            $maxSegments = ceil(strlen($text) / 70);
        }

        if (!is_null($maxSegments)) {
            $this->props['maxSegments'] = $this->between($maxSegments, 1, 6);
        }

        if (!is_null($messageTtlSec)) {
            $this->props['messageTtlSec'] = $this->between($messageTtlSec, 0, 86400);
        }
    }
}