<?php

namespace Kyivstar\Api\Tests\Unit;

use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Traits\ValueValidator;
use Kyivstar\Api\Exceptions\ValueIsEmptyException;
use Kyivstar\Api\Exceptions\ValueNotUrlException;

class ValueValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = new class {
            use ValueValidator;

            public function expose($method, ...$values)
            {
                return $this->{$method}(...$values);
            }
        };
    }

    public function testValueNotEmpty()
    {
        $value = fake()->word();
        $this->assertEquals($value, $this->mock->expose('notEmpty', $value));

        $value = fake()->sentence();
        $this->assertEquals($value, $this->mock->expose('notEmpty', $value));

        $this->expectException(ValueIsEmptyException::class);

        $this->mock->expose('notEmpty', fake()->randomElement([null, '', false, 0]));
    }

    public function testValidateUrl()
    {
        $value = fake()->url();
        $this->assertEquals($value, $this->mock->expose('isUrl', $value));

        $value = fake()->imageUrl();
        $this->assertEquals($value, $this->mock->expose('isUrl', $value));

        $this->expectException(ValueNotUrlException::class);

        $fakes = [fake()->word(),
                  fake()->boolean(),
                  fake()->sentence(),
                  fake()->numberBetween(0, 10),
                  null];

        $this->mock->expose('isUrl', fake()->randomElement($fakes));
    }

    public function testNumberBetween()
    {
        $max = fake()->numberBetween(-10, 10);
        $min = $max - fake()->randomNumber();
        $value = fake()->numberBetween($min, $max);

        $this->assertEquals($value, $this->mock->expose('between', $value, $min, $max));
    }
}