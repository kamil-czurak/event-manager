<?php

use common\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Event $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Wydarzenia', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="event-view">

    <div class="col-lg-7">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Aktualizuj', ['update', 'event_id' => $model->event_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('lbl', 'Delete'), ['delete', 'event_id' => $model->event_id], [
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
                'event_id',
                [
                    'attribute' => 'client_id',
                    'value' => $model->client->name,
                ],
                'name',
                [
                    'attribute' => 'status',
                    'value' => $model::getStatusMap($model->status),
                ],
                'contact_coordinator_name',
                'contact_coordinator_phone',
                'city',
                'street',
                'street_number',
                'zipcode',
                'comment',
                'ready_at',
                'start_at',
                'end_at',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>

    <div class="col-lg-5">
        <h2>Załączniki</h2>

        <p>
            <?= Html::a('Dodaj załącznik', ['/event-attachment-crud/create', 'event_id' => $model->event_id], ['class' => 'btn btn-primary']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => (new ActiveDataProvider(['query' => $model->getEventAttachments()])),
            'columns' => [
                [
                    'attribute' => 'file',
                    'format' => 'html',
                    'label' => 'Plik',
                    'value' => function(\common\models\EventAttachment $model) {
                        return Html::getFilePreview($model->file);
                    }
                ],
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'buttonOptions' => [
                        'class' => 'btn btn-xs btn-default',
                    ],
                    'urlCreator' => function ($action, \common\models\EventAttachment $eventAttachment) {
                        return Url::toRoute(["/event-attachment-crud/{$action}", 'attachment_id' => $eventAttachment->attachment_id]);
                    },
                    'visibleButtons' => [
                        'update' => false,
                        'delete' => true,
                        'view' => false,
                    ],
                ],
            ],
        ]); ?>

        <h2>Zadania</h2>

        <p>
            <?= Html::a('Dodaj zadanie', ['/event-task-crud/create', 'event_id' => $model->event_id], ['class' => 'btn btn-primary']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => (new ActiveDataProvider(['query' => $model->getEventTasks()])),
            'columns' => [
                [
                    'attribute' => 'name',
                    'value' => function(\common\models\EventTask $model) {
                        return $model->name;
                    }
                ],
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'buttonOptions' => [
                        'class' => 'btn btn-xs btn-default',
                    ],
                    'urlCreator' => function ($action, \common\models\EventTask $eventTask) {
                        return Url::toRoute(["/event-task-crud/{$action}", 'task_id' => $eventTask->task_id]);
                    },
                    'visibleButtons' => [
                        'update' => true,
                        'delete' => true,
                        'view' => true,
                    ],
                ],
            ],
        ]); ?>
    </div>

</div>
