<?php

use common\models\Event;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\EventSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Wydarzenia';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Stwórz', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'event_id',
            [
                'attribute' => 'client_id',
                'filter' => \common\models\Client::getStatusMap(),
                'value' => function (Event $model) {
                    return $model->client->name;
                }
            ],
            'name',
            [
                'attribute' => 'status',
                'filter' => Event::getStatusMap(),
                'value' => function (Event $model) {
                    return Event::getStatusMap($model->status);
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Event $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'event_id' => $model->event_id]);
                 }
            ],
        ],
    ]); ?>


</div>
