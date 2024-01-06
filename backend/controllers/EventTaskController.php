<?php

namespace backend\controllers;

use common\models\EventTask;
use backend\models\EventTaskSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventTaskCrudController implements the CRUD actions for EventTask model.
 */
class EventTaskController extends Controller
{

    /**
     * Updates an existing EventTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $task_id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionStatus($task_id)
    {
        $model = $this->findModel($task_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['/']);
        }

        return $this->render('status', [
            'model' => $model,
        ]);
    }
    /**
     * Finds the EventTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $task_id ID
     * @return EventTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($task_id)
    {
        if (($model = EventTask::findOne(['task_id' => $task_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
