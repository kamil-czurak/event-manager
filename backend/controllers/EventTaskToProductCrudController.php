<?php

namespace backend\controllers;

use common\models\EventTaskToProduct;
use backend\models\EventTaskToProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventTaskToProductCrudController implements the CRUD actions for EventTaskToProduct model.
 */
class EventTaskToProductCrudController extends Controller
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
     * Lists all EventTaskToProduct models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EventTaskToProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EventTaskToProduct model.
     * @param int $event_task_to_product_id Event Task To Product ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($event_task_to_product_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($event_task_to_product_id),
        ]);
    }

    /**
     * Creates a new EventTaskToProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate(int $task_id)
    {
        $model = new EventTaskToProduct();
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
     * Updates an existing EventTaskToProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $event_task_to_product_id Event Task To Product ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($event_task_to_product_id)
    {
        $model = $this->findModel($event_task_to_product_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['/event-task-crud/view', 'task_id' => $model->task_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EventTaskToProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $event_task_to_product_id Event Task To Product ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($event_task_to_product_id)
    {
        $model = $this->findModel($event_task_to_product_id);
        $task_id = $model->task_id;
        $model->delete();

        return $this->redirect(['/event-task-crud/view', 'task_id' => $model->task_id]);
    }

    /**
     * Finds the EventTaskToProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $event_task_to_product_id Event Task To Product ID
     * @return EventTaskToProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($event_task_to_product_id)
    {
        if (($model = EventTaskToProduct::findOne(['event_task_to_product_id' => $event_task_to_product_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
