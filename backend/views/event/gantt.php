<?php

use common\models\Event;
use yii\helpers\Html;
use yii\web\View;

/** @var yii\web\View $this */
/** @var Event $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
$data = json_encode(\common\services\EventService::prepareDataForGant($model));
$this->registerJs("

var tasks = {$data};
var gantt = new Gantt('#gantt', tasks, {
    language: 'pl',
});
gantt.change_view_mode('Quarter Day')
", View::POS_END);

$this->registerCss("
    .info-container {
        text-align: center;
        margin: 20px;
        font-family: Arial, sans-serif;
    }

    .info-text {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .info-button {
        background-color: #007BFF;
        color: #fff;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .info-button:hover {
        background-color: #0056b3;
    }
");
?>
<div class="event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($data == '[]'):?>
        <div class="info-container">
            <p class="info-text">Aby wygenerować wykres, dodaj zadania.</p>
            <?= Html::a('Dodaj zadanie', ['/event-crud/view', 'event_id' => $model->event_id], ['class' => 'btn btn-primary']);?>
        </div>
    <?php endif;?>

    <div id="gantt"></div>
</div>
