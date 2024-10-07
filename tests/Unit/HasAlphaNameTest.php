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

    /**
     * Check default from config
     * and use different for services
     */
    public function testAlphaName()
    {
        $smsAlphaName = fake()->word();
        $smsService = $this->mock->Sms($smsAlphaName);

        $viberAlphaName = fake()->word();
        $viberService = $this->mock->Viber($viberAlphaName);

        $this->assertEquals($smsAlphaName, $smsService->getAlphaName());
        $this->assertNotEquals($smsAlphaName, $this->mock->getAlphaName());

        $this->assertEquals($viberAlphaName, $viberService->getAlphaName());
        $this->assertNotEquals($viberAlphaName, $this->mock->getAlphaName());

        $this->expectException(ValueIsEmptyException::class);
        $this->mock->Sms()->setAlphaName('');
    }
}