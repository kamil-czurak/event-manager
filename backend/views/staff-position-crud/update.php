<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StaffPosition $model */

$this->title = 'Aktualizuj stanowisko: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stanowiska', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'position_id' => $model->position_id]];
$this->params['breadcrumbs'][] = 'Aktualizuj';
?>
<div class="staff-position-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
