<?php declare(strict_types=1);

namespace Kyivstar\Api\Dto;

class Sms extends Message
{
    const SEGMENT_SIZE = 70;

    const MAX_SEGMENT_COUNT = 6;

    public int $maxSegments;
    
    /**
     * Max segments will be calculated on
     *
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
        parent::__construct($from, $to, $text, $messageTtlSec);

        $computedSegments = (int) ceil(strlen($text) / self::SEGMENT_SIZE);

        if ($computedSegments > 1) {
            $this->maxSegments = $this->between($computedSegments, 1, self::MAX_SEGMENT_COUNT);
        }
    }
}
