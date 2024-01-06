<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/** @var yii\web\View $this */
/** @var common\models\EventTask $model */

$this->title = 'Aktualizuj status zadania: '.$model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="event-task-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'status')->dropDownList(\common\models\EventTask::getStatusMap(), ['prompt' => 'Wybierz']) ?>


        <div class="form-group">
            <?= Html::submitButton('Zapisz', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
