<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Staff $model */

$this->title = 'Utworz pracownika';
$this->params['breadcrumbs'][] = ['label' => 'Pracownicy', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
