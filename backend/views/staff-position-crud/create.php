<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StaffPosition $model */

$this->title = 'Utworz stanowisko';
$this->params['breadcrumbs'][] = ['label' => 'Stanowiska', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-position-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
