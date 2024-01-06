<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>
    <?php if ($model->getIsNewRecord()):?>
        <?= $form->field($model, 'password')->passwordInput() ?>
    <?php endif;?>

    <?= $form->field($model, 'status')->dropDownList(User::getStatusMap(), ['prompt' => 'Wybierz']) ?>

    <?= $form->field($model, 'assignedRole')->dropDownList(User::getRolesMap(), ['prompt' => 'Wybierz']); ?>

    <div class="form-group">
        <?= Html::submitButton('Zapisz', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
