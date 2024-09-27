<?php

namespace Kyivstar\Api\Dto\Viber;

use Kyivstar\Api\Exceptions\ValueException;
use Kyivstar\Api\Traits\PropsIterator;
use Kyivstar\Api\Traits\ValueValidator;

class ContentExtended implements \Iterator
{
    use ValueValidator, PropsIterator;

    /**
     * @param string $img
     * @param string|null $caption
     * @param string|null $action
     * @throws ValueException
     */
    public function __construct(string  $img,
                                ?string $caption = null,
                                ?string $action = null)
    {
        $this->props = ['img' => $this->isUrl($img)];

        if (!empty($caption)) {
            $this->props['caption'] = $caption;
        }

        if (!is_null($action)) {
            $this->props['action'] = $this->isUrl($action);
        }
    }
}