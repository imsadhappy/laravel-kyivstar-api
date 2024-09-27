<?php

namespace Kyivstar\Api\Services;

use Illuminate\Http\Client\RequestException;
use Kyivstar\Api\Dto\Viber\Promotion;
use Kyivstar\Api\Dto\Viber\Transaction;
use Kyivstar\Api\Exceptions\ValueException;
use Kyivstar\Api\Traits\HasAlphaName;
use Kyivstar\Api\Traits\ValueValidator;

class ViberService extends HttpService
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
        $this->url = "https://api-gateway.kyivstar.ua/{$this->notEmpty($server)}/rest/{$this->notEmpty($version)}/viber";

        $this->setAlphaName($alphaName);

        parent::__construct($authentication);
    }

    /**
     * @param string $to
     * @param string $text
     * @return string
     * @throws RequestException|ValueException
     */
    public function transaction(string $to, string $text): string
    {
        $transaction = new Transaction($this->alphaName, $to, $text);

        return $this->try('post', '/transaction', $transaction)->json('msgId');
    }

    /**
     * @param string $to
     * @param string $text
     * @return string
     * @throws RequestException|ValueException
     */
    public function promotion(string $to, string $text): string
    {
        $promotion = new Promotion($this->alphaName, $to, $text);

        return $this->try('post', '/promotion', $promotion)->json('msgId');
    }

    /**
     * @param string $msgId
     * @return string
     * @throws RequestException|ValueException
     */
    public function status(string $msgId): string
    {
        $msgId = $this->notEmpty($msgId);

        return $this->try('get', "/status/{$msgId}")->json('status');
    }
}