<?php

namespace Kyivstar\Api\Tests\Unit;

use Kyivstar\Api\Exceptions\ValueNotUrlException;
use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Dto\Viber\Promotion;

class ViberPromotionTest extends TestCase
{
    /**
     * Check correct toArray conversion incl. of nested objects
     */
    public function testValidViberPromotion()
    {
        $promotion = new Promotion(fake()->word(), fake()->phoneNumber(), fake()->text(), null, fake()->imageUrl());

        $this->assertIsArray($promotion->toArray());
    }
}