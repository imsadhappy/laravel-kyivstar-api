<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Dto\Sms;
use Kyivstar\Api\Traits\HasAlphaName;
use Kyivstar\Api\Traits\ValueValidator;
use Kyivstar\Api\Interfaces\MessengerInterface;

final class SmsService extends JsonHttpService implements MessengerInterface
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

    public function send(string $to, string $text, ?int $messageTtlSec = null): string
    {
        $sms = new Sms($this->alphaName, $to, $text, $messageTtlSec);

        return $this->post($sms->toArray())->json('msgId');
    }

    public function status(string $id): string
    {
        $id = $this->notEmpty($id);

        return $this->get("/{$id}")->json('status');
    }
}
