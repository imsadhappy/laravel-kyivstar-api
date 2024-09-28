<?php

namespace Kyivstar\Api;

use Kyivstar\Api\Services\AuthenticationService;
use Kyivstar\Api\Services\SmsService;
use Kyivstar\Api\Services\ViberService;
use Kyivstar\Api\Exceptions\ValueException;
use Kyivstar\Api\Traits\ValueValidator;

/**
 * Class KyivstarApi.
 */
class KyivstarApi
{
    use ValueValidator;

    private string $server;

    private string $version;

    private string $alphaName;

    private AuthenticationService $authentication;

    public function __construct(array $config)
    {
        $this->isValidConfig($config);
        $this->version = $config['version'];
        $this->server = $config['server'];
        $this->alphaName = $config['alpha_name'];
        $this->authentication = new AuthenticationService($config['client_id'], $config['client_secret']);
    }

    /**
     * @param string|null $alphaName
     * @return SmsService
     * @throws ValueException
     */
    public function Sms(?string $alphaName = null): SmsService
    {
        return new SmsService($this->server,
                              $this->version,
                              $this->authentication,
                              $alphaName ?? $this->alphaName);
    }

    /**
     * @param string|null $alphaName
     * @return ViberService
     * @throws ValueException
     */
    public function Viber(?string $alphaName = null): ViberService
    {
        return new ViberService($this->server,
                                $this->version,
                                $this->authentication,
                                $alphaName ?? $this->alphaName);
    }

    public function getServer(): string
    {
        return $this->server;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getAlphaName(): string
    {
        return $this->alphaName;
    }
}
