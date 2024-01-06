<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\StaffPosition $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stanowiska', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="staff-position-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Aktualizuj', ['update', 'position_id' => $model->position_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('lbl', 'Delete'), ['delete', 'position_id' => $model->position_id], [
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
            'position_id',
            'name',
            [
                'attribute' => 'status',
                'value' => $model::getStatusMap($model->status),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
