<?php declare(strict_types=1);

namespace Kyivstar\Api\Dto;

class Sms extends Message
{
    const MAX_TTL = 86400;

    const SEGMENT_SIZE = 70;

    const MAX_SEGMENT_COUNT = 6;

    public int $maxSegments;
    
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
                                ?int   $messageTtlSec = 86400)
    {
        parent::__construct($from, $to, $text, $this->between($messageTtlSec, 0, self::MAX_TTL));

        if (strlen($text) > 70) {
            $maxSegments = (int) ceil(strlen($text) / self::SEGMENT_SIZE);
        }

        if (!empty($maxSegments)) {
            $this->maxSegments = $this->between($maxSegments, 1, self::MAX_SEGMENT_COUNT);
        }
    }
}
