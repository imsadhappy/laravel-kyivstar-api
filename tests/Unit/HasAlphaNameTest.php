<?php

namespace Kyivstar\Api\Tests\Unit;

use Kyivstar\Api\KyivstarApi;
use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Exceptions\ValueIsEmptyException;

class HasAlphaNameTest extends TestCase
{
    private KyivstarApi $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = $this->newApiInstance();
    }

    public function testAlphaName()
    {
        $smsAlphaName = fake()->word();
        $viberAlphaName = fake()->word();

        $this->assertIsString($this->mock->getAlphaName());

        $this->assertEquals($smsAlphaName, $this->mock->Sms($smsAlphaName)->getAlphaName());

        $this->assertEquals($viberAlphaName, $this->mock->Viber($viberAlphaName)->getAlphaName());

        $this->expectException(ValueIsEmptyException::class);

        $this->mock->Sms()->setAlphaName('');
    }
}