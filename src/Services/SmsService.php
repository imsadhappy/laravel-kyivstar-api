<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Dto\Sms;
use Kyivstar\Api\Traits\HasAlphaName;
use Kyivstar\Api\Traits\ValueValidator;
use Kyivstar\Api\Exceptions\ValueException;
use Illuminate\Http\Client\RequestException;

class SmsService extends JsonHttpService
{
    use ValueValidator, HasAlphaName;

    /**
     * @param string $server
     * @param string $version
     * @param AuthenticationService $authentication
     * @param string $alphaName
     * @throws ValueException
     */
    public function __construct(string                $server,
                                string                $version,
                                AuthenticationService $authentication,
                                string                $alphaName)
    {
        $this->url = "https://api-gateway.kyivstar.ua/{$this->notEmpty($server)}/rest/{$this->notEmpty($version)}/sms";

        $this->setAlphaName($alphaName);

        parent::__construct($authentication);
    }

    /**
     * @param string $to
     * @param string $text
     * @return string
     * @throws RequestException|ValueException
     */
    public function send(string $to, string $text): string
    {
        $sms = new Sms($this->alphaName, $to, $text);

        return $this->try('post', '', $sms->toArray())->json('msgId');
    }

    /**
     * @param string $msgId
     * @return string
     * @throws RequestException|ValueException
     */
    public function status(string $msgId): string
    {
        $msgId = $this->notEmpty($msgId);

        return $this->try('get', "/{$msgId}")->json('status');
    }
}
