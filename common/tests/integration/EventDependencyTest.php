<?php

namespace common\tests\integration;

use common\fixtures\UserFixture;
use common\models\Client;
use common\models\Event;
use common\models\EventTask;
use common\models\EventTaskToStaff;
use common\models\Product;
use common\models\ProductCategory;
use common\models\Staff;
use common\models\StaffPosition;
use common\tests\unit\IntegrityException;
use common\tests\unit\InvalidConfigException;
use Faker\Factory;
use yii\base\InvalidCallException;

class EventDependencyTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var Factory $faker */
    protected $faker;

    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }

    protected function _before()
    {
        $this->faker = Factory::create();
    }

    public function testAssignTasksTwoUserNotBlocked()
    {
        $event = $this->createEvent();
        $staff = $this->createStaff();

        $task = new EventTask();
        $task->event_id = $event->event_id;
        $task->name = $this->faker->sentence(3);
        $task->planned_start_at = date('Y-m-d H:i:s', time());
        $task->planned_finished_at = date('Y-m-d H:i:s', strtotime('+1 day', time()));
        $task->status = EventTask::STATUS_ACTIVE;
        $task->save();

        $eventTaskToStaff = new EventTaskToStaff();
        $eventTaskToStaff->task_id = $task->task_id;
        $eventTaskToStaff->staff_id = $staff->staff_id;
        $eventTaskToStaff->save();

        $secondTask = new EventTask();
        $secondTask->event_id = $event->event_id;
        $secondTask->name = $this->faker->sentence(3);
        $secondTask->planned_start_at = date('Y-m-d H:i:s', strtotime('+2 day', time()));
        $secondTask->planned_finished_at = date('Y-m-d H:i:s', strtotime('+3 day', time()));
        $secondTask->status = EventTask::STATUS_ACTIVE;
        $secondTask->save();

        $secondEventTaskToStaff = new EventTaskToStaff();
        $secondEventTaskToStaff->task_id = $secondTask->task_id;
        $secondEventTaskToStaff->staff_id = $staff->staff_id;

        $this->assertTrue($eventTaskToStaff->validate());
    }

    public function testAssignTwoTasksToUserBlocked()
    {
        $event = $this->createEvent();
        $staff = $this->createStaff();

        $task = new EventTask();
        $task->event_id = $event->event_id;
        $task->name = $this->faker->sentence(3);
        $task->planned_start_at = date('Y-m-d H:i:s', time());
        $task->planned_finished_at = date('Y-m-d H:i:s', strtotime('+1 day', time()));
        $task->status = EventTask::STATUS_ACTIVE;
        $task->save();

        $eventTaskToStaff = new EventTaskToStaff();
        $eventTaskToStaff->task_id = $task->task_id;
        $eventTaskToStaff->staff_id = $staff->staff_id;
        $eventTaskToStaff->save();

        $secondTask = new EventTask();
        $secondTask->event_id = $event->event_id;
        $secondTask->name = $this->faker->sentence(3);
        $secondTask->planned_start_at = date('Y-m-d H:i:s', time());
        $secondTask->planned_finished_at = date('Y-m-d H:i:s', strtotime('+3 day', time()));
        $secondTask->status = EventTask::STATUS_ACTIVE;
        $secondTask->save();

        $secondEventTaskToStaff = new EventTaskToStaff();
        $secondEventTaskToStaff->task_id = $secondTask->task_id;
        $secondEventTaskToStaff->staff_id = $staff->staff_id;

        $this->assertFalse($secondEventTaskToStaff->validate());
    }


    private function createEvent(): Event
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

        return $event;
    }

    private function createProduct(): Product
    {
        $productCategory = new ProductCategory();
        $productCategory->name = $this->faker->sentence(3);
        $productCategory->status = ProductCategory::STATUS_ACTIVE;
        $productCategory->save();

        $product = new Product();
        $product->name = $this->faker->sentence(3);
        $product->comment = $this->faker->text;
        $product->quantity = $this->faker->numberBetween(1, 1000);
        $product->category_id = $productCategory->category_id;
        $product->save();

        return $product;
    }

    private function createStaff(): Staff
    {
        $staffPosition = new StaffPosition();
        $staffPosition->name = $this->faker->sentence(3);
        $staffPosition->status = StaffPosition::STATUS_ACTIVE;
        $staffPosition->save();

        $staff = new Staff();
        $staff->position_id = $staffPosition->position_id;
        $staff->user_id = 1;
        $staff->status = Staff::STATUS_ACTIVE;
        $staff->save();

        return $staff;
    }
}
