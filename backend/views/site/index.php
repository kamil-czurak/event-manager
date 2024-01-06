<?php

use common\services\EventService;
use yii\web\View;
/** @var View $this */

$this->title = 'Wydarzenia';
$data = json_encode(\common\services\EventService::prepareDataForUserGant());
$this->registerJs("

var tasks = {$data};
var gantt = new Gantt('#gantt', tasks, {
    language: 'pl',
});
gantt.change_view_mode('Quarter Day')
", View::POS_END);
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2>Nadchodzące wydarzenia</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?php foreach (EventService::getComingEvents() as $event):?>
                            <article class="media event">
                                <a class="pull-left date" style="width: 62px">
                                    <p class="month"><?= Yii::t('lbl', date('F', strtotime($event->start_at)));?></p>
                                    <p class="day"><?= date('j', strtotime($event->start_at));?></p>
                                </a>
                                <div class="media-body">
                                    <a class="title" href="<?= \yii\helpers\Url::to(['event/gantt', 'event_id' => $event->event_id]);?>"><?= $event->name;?></a>
                                    <p><?= $event->comment;?></p>
                                </div>
                            </article>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="x_panel tile">
                    <div class="x_title">
                        <h2>Twoje zadania</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?php foreach (EventService::getUserTasks() as $eventTask):?>
                            <article class="media event">
                                <a class="pull-left date" style="width: 62px">
                                    <p class="month"><?= Yii::t('lbl', date('F', strtotime($eventTask->planned_start_at)));?></p>
                                    <p class="day"><?= date('j', strtotime($eventTask->planned_start_at));?></p>
                                </a>
                                <div class="media-body">
                                    <a class="title" href="<?= \yii\helpers\Url::to(['event-task/status', 'task_id' => $eventTask->task_id]);?>"><?= $eventTask->event->name;?>: <?= $eventTask->name;?></a>
                                    <p><?= \common\models\EventTask::getStatusMap($eventTask->status);?></p>
                                </div>
                            </article>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h2>Twój harmonogram</h2>

                <div id="gantt"></div>
            </div>
        </div>

    </div>
</div>
