<?php declare(strict_types=1);

namespace Kyivstar\Api\Dto\Viber;

use Kyivstar\Api\Dto\Message;

class Transaction extends Message
{
    const MAX_TTL = 1209600;
    
    /**
     * @param string $from
     * @param string $to
     * @param string $text
     * @param int|null $messageTtlSec
     */
    public function __construct(string $from,
                                string $to,
                                string $text,
                                ?int   $messageTtlSec = 1209600)
    {
        parent::__construct($from, $to, $text, $this->between($messageTtlSec, 30, self::MAX_TTL));
    }
}
