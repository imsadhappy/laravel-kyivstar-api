<?php declare(strict_types=1);

namespace Kyivstar\Api\Tests\Feature;

use Kyivstar\Api\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SmsServiceTest extends TestCase
{
    public function testSmsService()
    {
        $version = $this->getApiVersion();

        if ('v1beta' === $version) {
            $this->runV1BetaTest();
        } else {
            $this->markTestSkipped(__CLASS__ . " for $version not found.");
        }
    }

    private function runV1BetaTest()
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