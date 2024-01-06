<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/** @var yii\web\View $this */
/** @var common\models\EventTask $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="event-task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'after_task_id')->dropDownList(\common\models\EventTask::getMap(whereCondition: ['event_id' => $model->event_id]), ['prompt' => 'Brak']) ?>

    <?= $form->field($model, 'planned_start_at')->widget(DateTimePicker::class) ?>

    <?= $form->field($model, 'planned_finished_at')->widget(DateTimePicker::class) ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\EventTask::getStatusMap(), ['prompt' => 'Wybierz']) ?>


    <div class="form-group">
        <?= Html::submitButton('Zapisz', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
