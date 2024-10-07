<?php declare(strict_types=1);

namespace Kyivstar\Api\Tests\Feature;

use Kyivstar\Api\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ViberServiceTest extends TestCase
{
    public function testViberService()
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

        $endpoint = $this->buildApiEndpoint('viber');
        $transactionMid = fake()->uuid();
        $promotionMid = fake()->uuid();
        $transactionStatus = fake()->randomElement(['accepted','delivered','viewed']);
        $promotionStatus = fake()->randomElement(['accepted','delivered','viewed']);

        Http::fake([
            "$endpoint/transaction" => Http::response(['mid' => $transactionMid], 200),
            "$endpoint/promotion" => Http::response(['mid' => $promotionMid], 200),
            "$endpoint/status/$transactionMid" => Http::response(['status' => $transactionStatus], 200),
            "$endpoint/status/$promotionMid" => Http::response(['status' => $promotionStatus], 200),
            "$endpoint/status/*" => Http::response(['errorMsg' => fake()->sentence()], 404),
        ]);

        $service = $this->newApiInstance()->Viber();

        $this->assertEquals($transactionMid, $service->transaction(fake()->phoneNumber(), fake()->sentence()));
        $this->assertEquals($transactionStatus, $service->status($transactionMid));

        $this->assertEquals($promotionMid, $service->promotion(...ViberPromotionTest::fakePromotion()));
        $this->assertEquals($promotionStatus, $service->status($promotionMid));

        $this->expectException(NotFoundHttpException::class);
        $service->status(fake()->uuid());
    }
}