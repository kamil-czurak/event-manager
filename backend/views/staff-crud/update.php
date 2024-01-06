<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Staff $model */

$this->title = 'Aktualizuj pracownika: ' . $model->first_name. ' '.$model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Staff', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->first_name. ' '.$model->last_name, 'url' => ['view', 'staff_id' => $model->staff_id]];
$this->params['breadcrumbs'][] = 'Aktualizuj';
?>
<div class="staff-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
