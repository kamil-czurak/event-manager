<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Event $model */

$this->title = 'Stwórz wydarzenie';
$this->params['breadcrumbs'][] = ['label' => 'Wydarzenia', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
