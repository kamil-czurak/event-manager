<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\UploadImageWidget;
use common\widgets\UploadFileWidget;

/** @var yii\web\View $this */
/** @var common\models\EventAttachment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="event-attachment-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'fileUpload')->widget(
        UploadFileWidget::class, ['file' => $model->file]
    ); ?>

    <div class="form-group">
        <?= Html::submitButton('Zapisz', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
