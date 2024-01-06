<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Staff $model */

$this->title = $model->first_name .' '. $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Pracownicy', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="staff-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Aktualizuj', ['update', 'staff_id' => $model->staff_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('lbl', 'Delete'), ['delete', 'staff_id' => $model->staff_id], [
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
            'staff_id',
            [
                'attribute' => 'position_id',
                'value' => $model->position->name,
            ],
            [
                'attribute' => 'user_id',
                'value' => $model->user->email,
            ],
            'first_name',
            'last_name',
            'phone',
            'comment',
            'bid',
            [
                'attribute' => 'status',
                'value' => $model::getStatusMap($model->status),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
