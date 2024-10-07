<?php declare(strict_types=1);

namespace Kyivstar\Api\Dto;

class Sms extends Message
{
    const SEGMENT_SIZE = 70;

    const MAX_SEGMENT_COUNT = 6;

    public int $maxSegments;

    public int $messageTtlSec;
    
    /**
     * @param string $from
     * @param string $to
     * @param string $text
     * @param int|null $maxSegments
     * @param int|null $messageTtlSec
     */
    public function __construct(string $from,
                                string $to,
                                string $text,
                                ?int   $maxSegments = null,
                                ?int   $messageTtlSec = null)
    {
        parent::__construct($from, $to, $text);

        if (strlen($text) > 70) {
            $maxSegments = (int) ceil(strlen($text) / self::SEGMENT_SIZE);
        }

        if (!empty($maxSegments)) {
            $this->maxSegments = $this->between($maxSegments, 1, self::MAX_SEGMENT_COUNT);
        }

        if (!is_null($messageTtlSec)) {
            $this->messageTtlSec = $this->between($messageTtlSec, 0, 86400);
        }
    }
}
