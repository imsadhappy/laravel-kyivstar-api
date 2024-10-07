<?php declare(strict_types=1);

namespace Kyivstar\Api\Tests\Unit;

use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Traits\ObjectToArray;

class ObjectToArrayTest extends TestCase
{
    private $mock;

    private $vars = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = new class {
            use ObjectToArray;
        };

        for ($i = 1; $i <= rand(5, 10); $i++) {
            $key = fake()->word();
            $value = fake()->{fake()->randomElement(['word', 'randomNumber'])}();
            $this->vars[$key] = $value;
            $this->mock->{$key} = $value;
        }
    }

    public function testObjectToArray()
    {
        $this->assertEquals($this->vars, $this->mock->toArray());
    }
}