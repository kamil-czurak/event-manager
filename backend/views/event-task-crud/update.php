<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EventTask $model */

$this->title = 'Aktualizuj zadanie: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->event->name, 'url' => ['/event-crud/view', 'event_id' => $model->event_id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'task_id' => $model->task_id]];
$this->params['breadcrumbs'][] = 'Aktualizuj';
?>
<div class="event-task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
