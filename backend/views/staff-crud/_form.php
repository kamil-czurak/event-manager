<?php

use common\models\StaffPosition;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Staff $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerJs("$('#open-modal-button').click(function(){
            $('#my-modal').modal('show');
        });
        
        $(document).on('submit', '#form-modal', function(e) {
    e.preventDefault();
    $.ajax({
        type: 'post',
        url: '/staff-position-crud/ajax-create',
        data: $(this).serialize(),
        success: function(data) {
            if (data.success) {
                $('#my-modal').modal('hide');
                var select = document.getElementById('staff-position_id');
                while (select.options.length > 1) {
                    select.remove(1);
                }
                let positions = Object.entries(data.positions);
                positions.forEach(function([id, text]) {
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

<div class="staff-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= \yii\helpers\Html::button('Dodaj stanowisko', ['class' => 'btn btn-success', 'id' => 'open-modal-button']) ?>
    <?= $form->field($model, 'position_id')->dropDownList(StaffPosition::getMap(), ['prompt' => "Wybierz"]) ?>

    <?= $form->field($model, 'user_id')->dropDownList(\common\models\User::getNotTakenUsersMap($model), ['prompt' => "Wybierz"]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList($model::getStatusMap()) ?>

    <div class="form-group">
        <?= Html::submitButton('Zapisz', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <?php
        Modal::begin([
            'header' => '<h2>Tworzenie stanowiska</h2>',
            'id' => 'my-modal',
            'size' => 'modal-lg',
        ]);

        $formModal = ActiveForm::begin([
            'id' => 'form-modal',
        ]);

        $client = new \common\models\StaffPosition();
        echo $formModal->field($client, 'name')->textInput();
        echo $formModal->field($client, 'status')->dropDownList($client::getStatusMap(), ['prompt' => 'Wybierz']);

        echo Html::submitButton('Zapisz', ['class' => 'btn btn-success']);
        ActiveForm::end();
        Modal::end();
    ?>

</div>
