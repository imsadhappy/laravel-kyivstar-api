<?php declare(strict_types=1);

namespace Kyivstar\Api\Tests\Feature;

use Kyivstar\Api\Dto\Viber\Promotion;
use Kyivstar\Api\Tests\TestCase;

class ViberPromotionTest extends TestCase
{
    /**
     * Check correct toArray conversion incl. of nested objects
     */
    public function testValidViberPromotion()
    {
        $promotion = new Promotion(fake()->word(), ...self::fakePromotion());

        $this->assertIsArray($promotion->toArray());
    }

    public static function fakePromotion(): array
    {
        return [fake()->phoneNumber(),
            fake()->sentence(),
            null,
            fake()->imageUrl(),
            fake()->sentence(),
            fake()->url()];
    }
}