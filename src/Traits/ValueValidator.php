<?php

namespace Kyivstar\Api\Traits;

use Kyivstar\Api\Exceptions\ConfigException;
use Kyivstar\Api\Exceptions\ValueIsEmptyException;
use Kyivstar\Api\Exceptions\ValueNotUrlException;
use Kyivstar\Api\Exceptions\ValueTooLongException;
use Kyivstar\Api\Exceptions\ValueTooShortException;
use Kyivstar\Api\Exceptions\ValueNotBetweenException;
use Kyivstar\Api\Exceptions\ValueIsNotAllowedException;

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
     * @param int|float $value
     * @param int|float $min
     * @param int|float $max
     * @return int|float
     * @throws ValueNotBetweenException
     */
    protected function between($value, $min, $max)
    {
        if (!is_null($value) && $value >= (float) $min && $value <= (float) $max) {
            return $value;
        }

        throw new ValueNotBetweenException($value, $min, $max, get_called_class());
    }

    /**
     * @param mixed $value
     * @param array $allowedOptions
     * @return mixed
     * @throws ValueIsNotAllowedException
     */
    protected function isOneOf($value, array $allowedOptions = [])
    {
        if (in_array($value, $allowedOptions)) {
            return $value;
        }

        throw new ValueIsNotAllowedException($value, $allowedOptions, get_called_class());
    }

    /**
     * @param array|null $config
     * @return array
     * @throws ConfigException|ValueIsNotAllowedException
     */
    protected function isValidConfig(?array $config = null)
    {
        if (empty($config)) {
            throw new ConfigException('empty array');
        }

        foreach (['version','server','alpha_name','client_id','client_secret'] as $key) {
            if (!isset($config[$key])) {
                throw new ConfigException("$key not set");
            }
            if (empty($config[$key])) {
                throw new ConfigException("$key empty");
            } 
        }

        $this->isOneOf($config['server'], ['mock','sandbox','production']);

        return $config;
    }
}
