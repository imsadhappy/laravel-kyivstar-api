<?php

namespace Kyivstar\Api\Traits;

use Kyivstar\Api\Exceptions\ConfigException;

trait ConfigValidator {

    /**
     * @param array|null $config
     * @return array
     * @throws ConfigException
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

        $serverOptions = ['mock','sandbox','production'];

        if (!in_array($config['server'], $serverOptions)) {
            throw new ConfigException("allowed server options are " . join('|', $serverOptions));
        }

        return $config;
    }
}
