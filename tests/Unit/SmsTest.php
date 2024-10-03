<?php

namespace Kyivstar\Api\Tests\Unit;

use Kyivstar\Api\Dto\Sms;
use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Exceptions\ValueNotBetweenException;

class SmsTest extends TestCase
{
    public function testValidSms()
    {
        $text = fake()->realTextBetween(10, 70*6);

        $sms = new Sms(fake()->word(), fake()->phoneNumber(), $text);

        $this->assertInstanceOf(Sms::class, $sms);
    }

    /**
     * Check correct SMS segmentation
     */
    public function testSmsTextTooLong()
    {
        $this->expectException(ValueNotBetweenException::class);

        $text = fake()->realTextBetween(70*6+1, 70*10);

        new Sms(fake()->word(), fake()->phoneNumber(), $text);
    }
}