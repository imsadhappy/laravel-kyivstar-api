<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Dto\Viber\Promotion;
use Kyivstar\Api\Dto\Viber\Transaction;
use Kyivstar\Api\Traits\HasAlphaName;
use Kyivstar\Api\Traits\ValueValidator;
use Kyivstar\Api\Interfaces\MessengerInterface;

final class ViberService extends JsonHttpService implements MessengerInterface
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

        parent::__construct('viber',
                            $this->notEmpty($server),
                            $this->notEmpty($version),
                            $authentication);
    }

    public function transaction(string $to, string $text, ?int $messageTtlSec = null): string
    {
        $transaction = new Transaction($this->alphaName, $to, $text, $messageTtlSec);

        return $this->post($transaction->toArray(), '/transaction')->json('mid');
    }

    public function promotion(string $to,
                              string $text,
                              ?int $messageTtlSec = null,
                              ?string $img = null,
                              ?string $caption = null,
                              ?string $action = null): string
    {
        $promotion = new Promotion($this->alphaName, $to, $text, $messageTtlSec, $img, $caption, $action);

        return $this->post($promotion->toArray(), '/promotion')->json('mid');
    }

    public function status(string $id): string
    {
        $id = $this->notEmpty($id);

        return $this->get("/status/$id")->json('status');
    }

    public function send(string $to, string $text, ?int $messageTtlSec = null): string
    {
        return $this->transaction($to, $text, $messageTtlSec);
    }
}
