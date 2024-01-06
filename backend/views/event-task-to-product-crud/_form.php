<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\EventTaskToProduct $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="event-task-to-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product_id')->dropDownList(\common\models\Product::getMap(), ['prompt' => 'Wybierz']) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Zapisz', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
