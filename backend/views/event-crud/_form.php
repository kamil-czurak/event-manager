<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/** @var yii\web\View $this */
/** @var common\models\Event $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerJs("$('#open-modal-button').click(function(){
            $('#my-modal').modal('show');
        });
        
        $(document).on('submit', '#form-modal', function(e) {
    e.preventDefault();
    $.ajax({
        type: 'post',
        url: '/client-crud/ajax-create',
        data: $(this).serialize(),
        success: function(data) {
            if (data.success) {
                $('#my-modal').modal('hide');
                var select = document.getElementById('event-client_id');
                while (select.options.length > 1) {
                    select.remove(1);
                }
                let clients = Object.entries(data.clients);
                clients.forEach(function([id, text]) {
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

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


    <?= \yii\helpers\Html::button('Dodaj klienta', ['class' => 'btn btn-success', 'id' => 'open-modal-button']) ?>

    <?= $form->field($model, 'client_id')->dropDownList(\common\models\Client::getMap(), ['prompt' => 'Wybierz']) ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\Event::getStatusMap(), ['prompt' => 'Wybierz']) ?>

    <?= $form->field($model, 'contact_coordinator_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_coordinator_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'street_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ready_at')->widget(DateTimePicker::class) ?>

    <?= $form->field($model, 'start_at')->widget(DateTimePicker::class) ?>

    <?= $form->field($model, 'end_at')->widget(DateTimePicker::class) ?>

    <div class="form-group">
        <?= Html::submitButton('Zapisz', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <?php
    Modal::begin([
        'header' => '<h2>Tworzenie klienta</h2>',
        'id' => 'my-modal',
        'size' => 'modal-lg',
    ]);

    $formModal = ActiveForm::begin([
        'id' => 'form-modal',
    ]);

    $client = new \common\models\Client();
    echo $formModal->field($client, 'name')->textInput();
    echo $formModal->field($client, 'status')->dropDownList($client::getStatusMap(), ['prompt' => 'Wybierz']);

    echo Html::submitButton('Zapisz', ['class' => 'btn btn-success']);
    ActiveForm::end();
    Modal::end();
    ?>

</div>
