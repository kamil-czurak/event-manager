<?php

use common\models\Staff;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\StaffPosition;

/** @var yii\web\View $this */
/** @var backend\models\StaffSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pracownicy';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Utworz pracownika', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Stanowiska', ['/staff-position-crud/index'], ['class' => 'btn btn-info']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'staff_id',
            'first_name',
            'last_name',
            [
                'attribute' => 'position_id',
                'filter' => StaffPosition::getMap(),
                'value' => function (Staff $model) {
                    return $model->position->name;
                }
            ],
            [
                'attribute' => 'status',
                'filter' => Staff::getStatusMap(),
                'value' => function (Staff $model) {
                    return Staff::getStatusMap($model->status);
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Staff $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'staff_id' => $model->staff_id]);
                 }
            ],
        ],
    ]); ?>


</div>
