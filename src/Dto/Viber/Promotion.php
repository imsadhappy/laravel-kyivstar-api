<?php declare(strict_types=1);

namespace Kyivstar\Api\Dto\Viber;

class Promotion extends Transaction
{
    public ContentExtended $contentExtended;
    
    /**
     * @param string $from
     * @param string $to
     * @param string $text
     * @param int|null $messageTtlSec
     * @param string|null $img
     * @param string|null $caption
     * @param string|null $action
     */
    public function __construct(string  $from,
                                string  $to,
                                string  $text,
                                ?int    $messageTtlSec = null,
                                ?string $img = null,
                                ?string $caption = null,
                                ?string $action = null)
    {
        parent::__construct($from, $to, $text, $messageTtlSec);

        if (!is_null($img)) {
            $this->contentExtended = new ContentExtended($img, $caption, $action);
        }
    }
}
