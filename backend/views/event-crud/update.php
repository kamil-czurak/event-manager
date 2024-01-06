<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Event $model */

$this->title = 'Update Event: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'event_id' => $model->event_id]];
$this->params['breadcrumbs'][] = 'Aktualizuj';
?>
<div class="event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
