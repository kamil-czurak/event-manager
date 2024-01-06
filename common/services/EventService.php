<?php

namespace common\services;

use common\models\AuthAssignment;
use common\models\AuthItemChild;
use common\helpers\Rbac;
use common\models\Event;
use common\models\EventTask;
use common\models\EventTaskToProduct;
use common\models\EventTaskToStaff;
use common\models\Product;
use Yii;
use yii\base\Exception;
use yii\helpers\Json;
use yii\rbac\Item;


class EventService
{

    public static function getComingEvents(int $limit = 5)
    {
        return Event::find()->where(['>', 'end_at', date("Y-m-d H:i:s")])->limit($limit)->all();
    }

    public static function getUserTasks(int $limit = 5)
    {
        $taskIds = EventTaskToStaff::find()->where(['staff_id' => Yii::$app->user->identity->getStaffId()])->select('task_id')->column();

        return EventTask::find()->andWhere(['in', 'task_id', $taskIds])->andWhere(['in', 'status', [EventTask::STATUS_ACTIVE, EventTask::STATUS_PENDING, EventTask::STATUS_BLOCKED, EventTask::STATUS_TODO]])->orderBy('planned_start_at ASC')->limit($limit)->all();
    }

    public static function prepareDataForGant(Event $event)
    {
        $data = [];
        foreach ($event->getEventTasks()->orderBy('planned_start_at ASC')->all() as $task) {
            $row = [
                'id' => 'task_'.$task->task_id,
                'name' => $task->name,
                'start' => $task->start_at ?? $task->planned_start_at,
                'end' => $task->finished_at ?? $task->planned_finished_at,
                'progression' => 50,
            ];

            if ($task->after_task_id) {
                $row['dependencies'] = 'task_'.$task->after_task_id;
            }

            $data[] = $row;
        }

        return $data;
    }

    public static function prepareDataForUserGant()
    {
        $data = [];
        $taskIds = EventTaskToStaff::find()->where(['staff_id' => Yii::$app->user->identity->getStaffId()])->select('task_id')->column();
        foreach (EventTask::find()->andWhere(['in', 'task_id', $taskIds])->andWhere(['in', 'status', [EventTask::STATUS_ACTIVE, EventTask::STATUS_PENDING, EventTask::STATUS_BLOCKED, EventTask::STATUS_TODO]])->orderBy('planned_start_at ASC')->all() as $task) {
            $row = [
                'id' => 'task_'.$task->task_id,
                'name' => $task->event->name .': '.$task->name,
                'start' => $task->start_at ?? $task->planned_start_at,
                'end' => $task->finished_at ?? $task->planned_finished_at,
                'progression' => 50,
            ];

            if ($task->after_task_id) {
                $row['dependencies'] = 'task_'.$task->after_task_id;
            }

            $data[] = $row;
        }

        return $data;
    }

    public static function getStaffInTime(int $staff_id, EventTask $task): bool
    {
        $eventTaskToStaffs = EventTaskToStaff::find()->andWhere(['staff_id' => $staff_id])->andWhere(['not', ['task_id' => $task->task_id]])->all();

        foreach ($eventTaskToStaffs as $eventTaskToStaff) {
            if (($task->planned_start_at >= $eventTaskToStaff->task->planned_start_at && $task->planned_start_at <= $eventTaskToStaff->task->planned_finished_at) ||
                ($task->planned_finished_at >= $eventTaskToStaff->task->planned_start_at && $task->planned_finished_at <= $eventTaskToStaff->task->planned_finished_at) ||
                ( $eventTaskToStaff->task->planned_start_at >= $task->planned_start_at &&  $eventTaskToStaff->task->planned_start_at <= $task->planned_finished_at) ||
                ($eventTaskToStaff->task->planned_finished_at >= $task->planned_start_at && $eventTaskToStaff->task->planned_finished_at <=$task->planned_finished_at)) {
                return true;
            }
        }

        return false;
    }
}
