<?php

use yii\db\Expression;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\EventTaskToStaff $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="event-task-to-staff-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'staff_id')->dropDownList(\common\models\Staff::getMap('last_name'), ['prompt' => 'Wybierz']) ?>

    <div class="form-group">
        <?= Html::submitButton('Zapisz', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
