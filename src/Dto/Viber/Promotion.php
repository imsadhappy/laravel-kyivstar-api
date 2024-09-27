<?php declare(strict_types=1);

namespace Kyivstar\Api\Dto\Viber;

use Kyivstar\Api\Exceptions\ValueException;

class Promotion extends Transaction
{
    /**
     * @param string $from
     * @param string $to
     * @param string $text
     * @param int|null $messageTtlSec
     * @param string|null $img
     * @param string|null $caption
     * @param string|null $action
     * @throws ValueException
     */
    public function __construct(string  $from,
                                string  $to,
                                string  $text,
                                ?int    $messageTtlSec = null,
                                ?string $img = null,
                                ?string $caption = null,
                                ?string $action = null)
    {
        parent::__construct($from, $to, $text);

        if (!is_null($messageTtlSec)) {
            $this->props['messageTtlSec'] = $this->between($messageTtlSec, 30, 1209600);
        }

        if (!is_null($img)) {
            $this->props['contentExtended'] = new ContentExtended($img, $caption, $action);
        }
    }
}