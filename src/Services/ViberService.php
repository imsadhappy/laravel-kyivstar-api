<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Dto\Viber\Promotion;
use Kyivstar\Api\Dto\Viber\Transaction;
use Kyivstar\Api\Traits\HasAlphaName;
use Kyivstar\Api\Traits\ValueValidator;
use Kyivstar\Api\Services\AuthenticationService;

class ViberService extends JsonHttpService
{
    use ValueValidator, HasAlphaName;

    /**
     * @param string $server
     * @param string $version
     * @param AuthenticationService $authentication
     * @param string $alphaName
     */
    public function __construct(string                $server,
                                string                $version,
                                AuthenticationService $authentication,
                                string                $alphaName)
    {
        $this->url = "https://api-gateway.kyivstar.ua/{$this->notEmpty($server)}/rest/{$this->notEmpty($version)}/viber";

        $this->setAlphaName($alphaName);

        parent::__construct($authentication);
    }

    /**
     * @param string $to
     * @param string $text
     * @return string
     */
    public function transaction(string $to, string $text): string
    {
        $transaction = new Transaction($this->alphaName, $to, $text);

        return $this->try('post', '/transaction', $transaction->toArray())->json('msgId');
    }

    /**
     * @param string $to
     * @param string $text
     * @return string
     */
    public function promotion(string $to, string $text): string
    {
        $promotion = new Promotion($this->alphaName, $to, $text);

        return $this->try('post', '/promotion', $promotion->toArray())->json('msgId');
    }

    /**
     * @param string $msgId
     * @return string
     */
    public function status(string $msgId): string
    {
        $msgId = $this->notEmpty($msgId);

        return $this->try('get', "/status/{$msgId}")->json('status');
    }
}
