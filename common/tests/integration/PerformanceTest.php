<?php

namespace common\tests\integration;

use common\models\Event;
use common\models\Product;
use Yii;
use yii\db\Migration;
use PHPUnit\Framework\TestCase;
use Faker\Factory;

class PerformanceTest extends TestCase
{
    const ITERATIONS = 100;

    protected function setUp(): void
    {
        parent::setUp();

        $migration = new Migration(['db' => Yii::$app->db]);
        $migration->up();
    }

    // Test wydajnościowy
    public function testPerformance()
    {
        /** @var Factory $faker */
        $faker = Factory::create();

        $totalExecutionTime = 0;

        for ($i = 0; $i < self::ITERATIONS; $i++) {
            $startTime = microtime(true);

            $event = new Event();
            $event->name = $faker->text();
            $event->status = Event::STATUS_ACTIVE;
            $event->contact_coordinator_name = $faker->name();
            $event->contact_coordinator_phone = $faker->phoneNumber;
            $event->city = $faker->city;
            $event->street = $faker->streetAddress;
            $event->street_number = $faker->buildingNumber;
            $event->save();

            $product = new Product();
            $product->name = $faker->text();
            $product->status = Product::STATUS_ACTIVE;
            $product->quantity = $faker->numberBetween(1, 1000);
            $product->save();

            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;

            $totalExecutionTime += $executionTime;
        }

        $averageExecutionTime = $totalExecutionTime / self::ITERATIONS;

        $this->assertLessThan(1.0, $averageExecutionTime);
    }

    protected function tearDown(): void
    {
        $migration = new Migration(['db' => Yii::$app->db]);
        $migration->down();

        parent::tearDown();
    }
}
