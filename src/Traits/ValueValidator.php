<?php

namespace Kyivstar\Api\Traits;

use Kyivstar\Api\Exceptions\ValueIsEmptyException;
use Kyivstar\Api\Exceptions\ValueNotUrlException;
use Kyivstar\Api\Exceptions\ValueTooLongException;
use Kyivstar\Api\Exceptions\ValueTooShortException;
use Kyivstar\Api\Exceptions\ValueNotBetweenException;

trait ValueValidator
{
    /**
     * @throws ValueIsEmptyException
     */
    protected function notEmpty($value)
    {
        if (!empty($value)) {
            return $value;
        }

        throw new ValueIsEmptyException($value, get_called_class());
    }

    /**
     * @throws ValueIsEmptyException|ValueNotUrlException
     */
    protected function isUrl($value)
    {
        if ($this->notEmpty($value) && filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        throw new ValueNotUrlException($value, get_called_class());
    }

    /**
     * @param $value
     * @param int $condition
     * @return string
     * @throws ValueTooShortException
     */
    protected function minLength($value, int $condition): string
    {
        if (!is_null($value) && strlen($value) >= $condition) {
            return $value;
        }

        throw new ValueTooShortException($value, $condition, get_called_class());
    }

    /**
     * @param $value
     * @param int $condition
     * @return string
     * @throws ValueTooLongException
     */
    protected function maxLength($value, int $condition): string
    {
        if (!is_null($value) && strlen($value) <= $condition) {
            return $value;
        }

        throw new ValueTooLongException($value, $condition, get_called_class());
    }

    /**
     * @param $value
     * @param $min
     * @param $max
     * @return mixed
     * @throws ValueNotBetweenException
     */
    protected function between($value, $min, $max)
    {
        if (!is_null($value) && $value >= (float) $min && $value <= (float) $max) {
            return $value;
        }

        throw new ValueNotBetweenException($value, $min, $max, get_called_class());
    }
}