<?php

namespace Kyivstar\Api\Traits;

trait HasAlphaName
{
    use ValueValidator;

    private string $alphaName;

    public function getAlphaName(): string
    {
        return $this->alphaName;
    }

    /**
     * Set alpha-name on runtime, overriding config|.env values
     *
     * @param string $alphaName
     * @return self
     */
    public function setAlphaName(string $alphaName): self
    {
        $this->alphaName = $this->notEmpty($alphaName);

        return $this;
    }
}
