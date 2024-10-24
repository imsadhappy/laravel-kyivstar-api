<?php

namespace Kyivstar\Api\Interfaces;

interface MessengerInterface
{
    /**
     * @param string $to receiver phone
     * @param string $text message text
     * @param int|null $messageTtlSec
     * @return string message id
     */
    public function send(string $to, string $text, ?int $messageTtlSec = null): string;

    /**
     * @param string $id message id
     * @return string - message status accepted|delivered|viewed
     */
    public function status(string $id): string;
}