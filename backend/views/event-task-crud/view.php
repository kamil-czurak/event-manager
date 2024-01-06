<?php

use common\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\EventTask $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->event->name, 'url' => ['/event-crud/view', 'event_id' => $model->event_id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="event-task-view">

    <div class="col-lg-7">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Aktualizuj', ['update', 'task_id' => $model->task_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('lbl', 'Delete'), ['delete', 'task_id' => $model->task_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'task_id',
                [
                    'attribute' => 'event_id',
                    'value' => $model->event->name,
                ],
                [
                    'attribute' => 'after_task_id',
                    'value' => $model->afterTask?->name ?? null,
                ],
                'name',
                'planned_start_at',
                'planned_finished_at',
                'start_at',
                'finished_at',
                [
                    'attribute' => 'status',
                    'value' => $model::getStatusMap($model->status),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>

    <div class="col-lg-5">
        <h2>Produkty</h2>

        <p>
            <?= Html::a('Dodaj produkt', ['/event-task-to-product-crud/create', 'task_id' => $model->task_id], ['class' => 'btn btn-primary']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => (new ActiveDataProvider(['query' => $model->getEventTaskToProducts()])),
            'columns' => [
                [
                    'attribute' => 'name',
                    'label' => 'Produkt',
                    'value' => function(\common\models\EventTaskToProduct $model) {
                        return $model->product->name;
                    }
                ],
                'quantity',
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'buttonOptions' => [
                        'class' => 'btn btn-xs btn-default',
                    ],
                    'urlCreator' => function ($action, \common\models\EventTaskToProduct $eventTaskToProduct) {
                        return Url::toRoute(["/event-task-to-product-crud/{$action}", 'event_task_to_product_id' => $eventTaskToProduct->event_task_to_product_id]);
                    },
                    'visibleButtons' => [
                        'update' => false,
                        'delete' => true,
                        'view' => false,
                    ],
                ],
            ],
        ]); ?>

        <h2>Pracownicy</h2>

        <p>
            <?= Html::a('Dodaj pracownika', ['/event-task-to-staff-crud/create', 'task_id' => $model->task_id], ['class' => 'btn btn-primary']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => (new ActiveDataProvider(['query' => $model->getEventTaskToStaff()])),
            'columns' => [
                [
                    'attribute' => 'name',
                    'label' => 'Imie i nazwisko',
                    'value' => function(\common\models\EventTaskToStaff $model) {
                        return $model->staff->first_name .' '. $model->staff->last_name;
                    }
                ],
                [
                    'attribute' => 'position',
                    'label' => 'Stanowisko',
                    'value' => function(\common\models\EventTaskToStaff $model) {
                        return $model->staff->position->name;
                    }
                ],
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'buttonOptions' => [
                        'class' => 'btn btn-xs btn-default',
                    ],
                    'urlCreator' => function ($action, \common\models\EventTaskToStaff $eventTaskToStaff) {
                        return Url::toRoute(["/event-task-to-staff-crud/{$action}", 'task_to_staff_id' => $eventTaskToStaff->task_to_staff_id]);
                    },
                    'visibleButtons' => [
                        'update' => false,
                        'delete' => true,
                        'view' => false,
                    ],
                ],
            ],
        ]); ?>
    </div>

</div>
