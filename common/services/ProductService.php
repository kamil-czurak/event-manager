<?php

namespace common\services;

use common\models\AuthAssignment;
use common\models\AuthItemChild;
use common\helpers\Rbac;
use common\models\EventTask;
use common\models\EventTaskToProduct;
use common\models\Product;
use Yii;
use yii\base\Exception;
use yii\rbac\Item;


class ProductService
{

    public static function getQuantityInTime(Product $product = null, EventTask $task)
    {
        $quantity = $product->quantity;
        $eventTaskToProducts = EventTaskToProduct::find()->andWhere(['product_id' => $product->product_id])->andWhere(['not', ['task_id' => $task->task_id]])->all();

        foreach ($eventTaskToProducts as $eventTaskToProduct) {
            if (($task->planned_start_at >= $eventTaskToProduct->task->planned_start_at && $task->planned_start_at <= $eventTaskToProduct->task->planned_finished_at) ||
                ($task->planned_finished_at >= $eventTaskToProduct->task->planned_start_at && $task->planned_finished_at <= $eventTaskToProduct->task->planned_finished_at) ||
                ( $eventTaskToProduct->task->planned_start_at >= $task->planned_start_at &&  $eventTaskToProduct->task->planned_start_at <= $task->planned_finished_at) ||
                ($eventTaskToProduct->task->planned_finished_at >= $task->planned_start_at && $eventTaskToProduct->task->planned_finished_at <=$task->planned_finished_at)) {
                $quantity -= $eventTaskToProduct->quantity;
            }
        }

        return $quantity;
    }

}
