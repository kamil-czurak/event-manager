<?php

use common\models\StaffPosition;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\StaffPositionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Stanowiska';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-position-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Utworz stanowisko', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Pracownicy', ['/staff-crud/index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'position_id',
            'name',
            [
                'attribute' => 'status',
                'filter' => StaffPosition::getStatusMap(),
                'value' => function (StaffPosition $model) {
                    return StaffPosition::getStatusMap($model->status);
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, StaffPosition $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'position_id' => $model->position_id]);
                 }
            ],
        ],
    ]); ?>


</div>
