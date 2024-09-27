<?php declare(strict_types=1);

namespace Kyivstar\Api\Dto\Viber;

use Kyivstar\Api\Dto\Message;
use Kyivstar\Api\Exceptions\ValueException;

class Transaction extends Message
{
    /**
     * @param string $from
     * @param string $to
     * @param string $text
     * @param int|null $messageTtlSec
     * @throws ValueException
     */
    public function __construct(string $from,
                                string $to,
                                string $text,
                                ?int   $messageTtlSec = null)
    {
        parent::__construct($from, $to, $text);

        if (!is_null($messageTtlSec)) {
            $this->props['messageTtlSec'] = $this->between($messageTtlSec, 30, 1209600);
        }
    }
}