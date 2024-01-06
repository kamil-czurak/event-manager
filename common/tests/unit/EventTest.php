<?php

namespace common\tests\unit;

use common\models\Client;
use common\models\Event;
use common\models\Product;
use common\models\ProductCategory;
use Faker\Factory;
use yii\base\InvalidCallException;

class EventTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var Factory $faker */
    protected $faker;

    protected function _before()
    {
        $this->faker = Factory::create();
    }

    public function testCreateEventWithClient()
    {
        $client = new Client();
        $client->name = $this->faker->sentence(3);
        $client->status = Client::STATUS_ACTIVE;
        $client->save();

        $event = new Event();
        $event->name = $this->faker->sentence(3);
        $event->comment = $this->faker->text;
        $event->client_id = $client->client_id;
        $event->status = Event::STATUS_ACTIVE;

        try {
            $event->save();
        } catch (InvalidConfigException | IntegrityException $e) {
            $this->fail('Nie można zapisać wydarzenia: ' . $e->getMessage());
        }

        $this->assertNotNull($event->event_id);
        $this->assertEquals($event->client_id, $client->client_id);
    }

    public function testCreateEventWithoutClient()
    {
        $event = new Event();
        $event->name = $this->faker->sentence(3);
        $event->comment = $this->faker->text;
        $event->status = Event::STATUS_ACTIVE;

        $this->assertNull($event->event_id);
    }

    public function testCreateEventWithoutRequiredData()
    {
        $product = new Event();

        $this->assertFalse($product->validate());
    }

    public function testAssignEventToNonexistentClients()
    {
        $event = new Event();
        $event->name = $this->faker->sentence(3);
        $event->comment = $this->faker->text;
        $event->status = Event::STATUS_ACTIVE;
        $event->client_id = -1;

        $event->save();
        $this->assertNull($event->event_id);
    }

    public function testDeleteEvent()
    {
        $client = new Client();
        $client->name = $this->faker->sentence(3);
        $client->status = Client::STATUS_ACTIVE;
        $client->save();

        $event = new Event();
        $event->name = $this->faker->sentence(3);
        $event->comment = $this->faker->text;
        $event->client_id = $client->client_id;
        $event->status = Event::STATUS_ACTIVE;
        $event->save();

        $this->assertNotNull($event->event_id);

        $event->delete();

        $this->assertNull(Event::findOne($event->event_id));
    }
}
