<?php

use common\widgets\UploadImageWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/** @var yii\web\View $this */
/** @var common\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
$this->registerJs("$('#open-modal-button').click(function(){
            $('#my-modal').modal('show');
        });
        
        $(document).on('submit', '#form-modal', function(e) {
    e.preventDefault();
    $.ajax({
        type: 'post',
        url: '/product-category-crud/ajax-create',
        data: $(this).serialize(),
        success: function(data) {
            if (data.success) {
                $('#my-modal').modal('hide');
                var select = document.getElementById('product-category_id');
                while (select.options.length > 1) {
                    select.remove(1);
                }
                let categories = Object.entries(data.categories);
                categories.forEach(function([id, text]) {
                    var option = document.createElement('option');
                    option.value = id;
                    option.text = text;
                    select.add(option);
                });
            }
        }
    });
});"

    , 3);
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= \yii\helpers\Html::button('Dodaj kategorie', ['class' => 'btn btn-success', 'id' => 'open-modal-button']) ?>
    <?= $form->field($model, 'category_id')->dropDownList(\common\models\ProductCategory::getMap(), ['prompt' => "Wybierz"])?>

    <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textarea() ?>

    <?= $form->field($model, 'status')->dropDownList($model::getStatusMap()) ?>

    <?= $form->field($model, 'mainImageUpload')->widget(
        UploadImageWidget::class, ['file' => $model->mainImage]
    ); ?>


    <div class="form-group">
        <?= Html::submitButton('Zapisz', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
        Modal::begin([
            'header' => '<h2>Tworzenie kategorii</h2>',
            'id' => 'my-modal',
            'size' => 'modal-lg',
        ]);

        $formModal = ActiveForm::begin([
            'id' => 'form-modal',
        ]);

        $client = new \common\models\ProductCategory();
        echo $formModal->field($client, 'name')->textInput();
        echo $formModal->field($client, 'status')->dropDownList($client::getStatusMap(), ['prompt' => 'Wybierz']);

        echo Html::submitButton('Zapisz', ['class' => 'btn btn-success']);
        ActiveForm::end();
        Modal::end();
    ?>

</div>
