<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Dto\Sms;
use Kyivstar\Api\Traits\HasAlphaName;
use Kyivstar\Api\Traits\ValueValidator;

final class SmsService extends JsonHttpService
{
    use ValueValidator, HasAlphaName;

    /**
     * @param string $server
     * @param string $version
     * @param string $alphaName
     * @param AuthenticationService $authentication
     */
    public function __construct(string $server,
                                string $version,
                                string $alphaName,
                                AuthenticationService $authentication)
    {
        $this->setAlphaName($alphaName);

        parent::__construct('sms',
                            $this->notEmpty($server),
                            $this->notEmpty($version),
                            $authentication);
    }

    /**
     * @param string $to
     * @param string $text
     * @param int|null $messageTtlSec
     * @return string
     */
    public function send(string $to, string $text, ?int $messageTtlSec = null): string
    {
        $sms = new Sms($this->alphaName, $to, $text, $messageTtlSec);

        return $this->post($sms->toArray())->json('msgId');
    }

    /**
     * @param string $msgId
     * @return string - status accepted|delivered|viewed
     */
    public function status(string $msgId): string
    {
        $msgId = $this->notEmpty($msgId);

        return $this->get("/{$msgId}")->json('status');
    }
}
