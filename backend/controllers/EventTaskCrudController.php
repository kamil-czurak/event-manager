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
class EventTaskCrudController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all EventTask models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EventTaskSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EventTask model.
     * @param int $task_id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($task_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($task_id),
        ]);
    }

    /**
     * Creates a new EventTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate(int $event_id)
    {
        $model = new EventTask();
        $model->event_id = $event_id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'task_id' => $model->task_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EventTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $task_id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($task_id)
    {
        $model = $this->findModel($task_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'task_id' => $model->task_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EventTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $task_id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($task_id)
    {
        $model = $this->findModel($task_id);
        $event_id = $model->event_id;
        $model->delete();

        return $this->redirect(['/event-crud/view', 'event_id' => $event_id]);
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
