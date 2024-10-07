<?php declare(strict_types=1);

namespace Kyivstar\Api\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Kyivstar\Api\Tests\VersionedTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SmsServiceTest extends VersionedTestCase
{
    public function testSmsService()
    {
        $this->runVersionTest();
    }

    protected function runV1BetaTest()
    {
        AuthenticationServiceTest::setupAuthenticationFacade();

        $endpoint = $this->buildApiEndpoint('sms');
        $msgId = fake()->uuid();
        $status = fake()->randomElement(['accepted','delivered','viewed']);

        Http::fake([
            $endpoint => Http::response(['msgId' => $msgId], 200),
            "$endpoint/$msgId" => Http::response(['status' => $status], 200),
            "$endpoint/*" => Http::response(['errorMsg' => fake()->sentence()], 404),
        ]);

        $service = $this->newApiInstance()->Sms();

        $this->assertEquals($msgId, $service->send(fake()->phoneNumber(), SmsTest::fakeText()));
        $this->assertEquals($status, $service->status($msgId));

        $this->expectException(NotFoundHttpException::class);
        $service->status(fake()->uuid());
    }
}