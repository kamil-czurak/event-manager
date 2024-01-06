<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EventTaskToStaff $model */

$this->title = 'Dodaj pracownika';
$this->params['breadcrumbs'][] = ['label' => $model->task->name, 'url' => ['/event-task-crud/view', 'task_id' => $model->task_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-task-to-staff-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
