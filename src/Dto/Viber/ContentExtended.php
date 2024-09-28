<?php

namespace Kyivstar\Api\Dto\Viber;

use Kyivstar\Api\Traits\ValueValidator;
use Kyivstar\Api\Exceptions\ValueException;

class ContentExtended
{
    use ValueValidator;

    public string $img;

    public string $caption;

    public string $action;

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
        $this->img = $this->isUrl($img);

        if (!empty($caption)) {
            $this->caption = $caption;
        }

        if (!is_null($action)) {
            $this->action = $this->isUrl($action);
        }
    }
}
