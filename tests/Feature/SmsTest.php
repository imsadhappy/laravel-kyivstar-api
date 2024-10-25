<?php declare(strict_types=1);

namespace Kyivstar\Api\Tests\Feature;

use Kyivstar\Api\Dto\Sms;
use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Exceptions\ValueNotBetweenException;

class SmsTest extends TestCase
{
    public function testValidSms()
    {
        $payload = [
            'from' => fake()->word(),
            'to' => fake()->phoneNumber(),
            'text' => self::fakeText()
        ];
        $expectedSegments = (int) ceil(strlen($payload['text']) / Sms::SEGMENT_SIZE);
        $sms = new Sms($payload['from'], $payload['to'], $payload['text']);

        // check expected segmentation
        if ($expectedSegments > 1) {
            $payload['maxSegments'] = $expectedSegments;
        }

        // also check toArray is implemented & used
        $this->assertEquals($payload, $sms->toArray());
    }

    /**
     * Check segments overflow
     */
    public function testSmsTextTooLong()
    {
        $this->expectException(ValueNotBetweenException::class);
        $charsOverflow = Sms::SEGMENT_SIZE * Sms::MAX_SEGMENT_COUNT + fake()->randomDigitNotZero();

        new Sms(fake()->word(),
                fake()->phoneNumber(),
                fake()->realTextBetween($charsOverflow, $charsOverflow * fake()->randomDigitNotZero()));
    }

    public static function fakeText(): string
    {
        $minChars = fake()->randomDigitNotZero() * fake()->randomDigitNotZero();

        return fake()->realTextBetween($minChars, Sms::SEGMENT_SIZE * Sms::MAX_SEGMENT_COUNT);
    }
}