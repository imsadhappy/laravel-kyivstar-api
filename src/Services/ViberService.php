<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Dto\Viber\Promotion;
use Kyivstar\Api\Dto\Viber\Transaction;
use Kyivstar\Api\Traits\HasAlphaName;
use Kyivstar\Api\Traits\ValueValidator;

class ViberService extends JsonHttpService
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

    /**
     * @param string $to
     * @param string $text
     * @return string
     */
    public function transaction(string $to, string $text): string
    {
        $transaction = new Transaction($this->alphaName, $to, $text);

        return $this->post($transaction->toArray(), '/transaction')->json('mid');
    }

    /**
     * @param string $to
     * @param string $text
     * @param int|null $messageTtlSec
     * @param string|null $img
     * @param string|null $caption
     * @param string|null $action
     * @return string
     */
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

    /**
     * @param string $mid
     * @return string
     */
    public function status(string $mid): string
    {
        $mid = $this->notEmpty($mid);

        return $this->get("/status/$mid")->json('status');
    }
}
