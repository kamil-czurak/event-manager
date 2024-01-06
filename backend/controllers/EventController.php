<?php

namespace backend\controllers;

use common\models\Event;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class EventController extends Controller
{

    public function actionGantt($event_id)
    {

        return $this->render('gantt', [
            'model' => $this->findModel($event_id),
        ]);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $event_id Event ID
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($event_id)
    {
        if (($model = Event::findOne(['event_id' => $event_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
