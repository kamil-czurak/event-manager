<?php

/* @var $attribute string */
/* @var $options array */
/* @var $pluginOptions array */
/* @var $file \common\models\File */
/* @var $model \yii\db\ActiveRecord */

?>
<?= \kartik\file\FileInput::widget([
    'model' => $model,
    'attribute' => $attribute,
    'options' => $options,
    'pluginOptions' => $pluginOptions,
]) ?>