<?php

namespace backend\controllers;

use common\models\EventTaskToStaff;
use backend\models\EventTaskToStaffSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventTaskToStaffCrudController implements the CRUD actions for EventTaskToStaff model.
 */
class EventTaskToStaffCrudController extends Controller
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
     * Lists all EventTaskToStaff models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EventTaskToStaffSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EventTaskToStaff model.
     * @param int $task_to_staff_id Task To Staff ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($task_to_staff_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($task_to_staff_id),
        ]);
    }

    /**
     * Creates a new EventTaskToStaff model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate(int $task_id)
    {
        $model = new EventTaskToStaff();
        $model->task_id = $task_id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['/event-task-crud/view', 'task_id' => $model->task_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EventTaskToStaff model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $task_to_staff_id Task To Staff ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($task_to_staff_id)
    {
        $model = $this->findModel($task_to_staff_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['/event-task-crud/view', 'task_id' => $model->task_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EventTaskToStaff model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $task_to_staff_id Task To Staff ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($task_to_staff_id)
    {
        $model = $this->findModel($task_to_staff_id);
        $task_id = $model->task_id;
        $model->delete();

        return $this->redirect(['/event-task-crud/view', 'task_id' => $model->task_id]);
    }

    /**
     * Finds the EventTaskToStaff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $task_to_staff_id Task To Staff ID
     * @return EventTaskToStaff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($task_to_staff_id)
    {
        if (($model = EventTaskToStaff::findOne(['task_to_staff_id' => $task_to_staff_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
