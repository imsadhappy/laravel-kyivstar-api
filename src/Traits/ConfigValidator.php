<?php

namespace Kyivstar\Api\Traits;

use Kyivstar\Api\Exceptions\ConfigException;

trait ConfigValidator {

    /**
     * @param array|null $config
     * @return array
     * @throws ConfigException
     */
    protected function isValidConfig(?array $config = null): array
    {
        if (empty($config)) {
            throw new ConfigException('empty array');
        }

        foreach (['version','server','alpha_name','client_id','client_secret'] as $key) {
            if (!isset($config[$key])) {
                throw new ConfigException("$key not set", ConfigException::MISSING_CONFIG);
            }
            if (empty($config[$key])) {
                throw new ConfigException("$key empty", ConfigException::EMPTY_VALUE);
            } 
        }

        $supportedVersions = ['v1beta'];

        if (!in_array($config['version'], $supportedVersions)) {
            throw new ConfigException("Version {$config['version']} is not supported", ConfigException::INVALID_VERSION);
        }

        $serverOptions = ['mock','sandbox','production'];

        if (!in_array($config['server'], $serverOptions)) {
            throw new ConfigException("allowed server options are " . join('|', $serverOptions), ConfigException::INVALID_SERVER);
        }

        return $config;
    }
}
