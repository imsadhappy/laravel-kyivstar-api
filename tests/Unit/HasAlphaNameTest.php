<?php

namespace Kyivstar\Api\Tests\Unit;

use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Exceptions\ValueIsEmptyException;

class HasAlphaNameTest extends TestCase
{
    public function testGetAlphaName()
    {
        $alphaName = $this->newApiInstance()->getAlphaName();

        $this->assertIsString($alphaName);
    }

    public function testSetAlphaName()
    {
        $alphaName = fake()->word();

        $sms = $this->newApiInstance()->Sms()->setAlphaName($alphaName);
        $viber = $this->newApiInstance()->Viber()->setAlphaName($alphaName);

        $this->assertEquals($alphaName, $sms->getAlphaName());
        $this->assertEquals($alphaName, $viber->getAlphaName());
    }

    public function testEmptyAlphaName()
    {
        $this->expectException(ValueIsEmptyException::class);

        $this->newApiInstance()->Sms()->setAlphaName('');
    }
}