<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EventAttachment $model */

$this->title = 'Stwórz załącznik';
$this->params['breadcrumbs'][] = ['label' => $model->event->name, 'url' => ['/event-crud/view', 'event_id' => $model->event_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-attachment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
