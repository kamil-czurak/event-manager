<?php

namespace backend\controllers;

use common\models\StaffPosition;
use backend\models\StaffPositionSearch;
use yii\bootstrap\ActiveForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use Yii;

/**
 * StaffPositionCrudController implements the CRUD actions for StaffPosition model.
 */
class StaffPositionCrudController extends Controller
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all StaffPosition models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StaffPositionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StaffPosition model.
     * @param int $position_id Position ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($position_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($position_id),
        ]);
    }

    /**
     * Creates a new StaffPosition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new StaffPosition();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'position_id' => $model->position_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StaffPosition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $position_id Position ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($position_id)
    {
        $model = $this->findModel($position_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'position_id' => $model->position_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionAjaxCreate()
    {
        $model = new StaffPosition();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->validate() && $model->save()) {
                return ['success' => true, 'message' => 'Formularz został zapisany pomyślnie.', 'positions' => StaffPosition::getMap()];
            } else {
                return ['success' => false, 'errors' => ActiveForm::validate($model)];
            }
        }
    }

    /**
     * Deletes an existing StaffPosition model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $position_id Position ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($position_id)
    {
        $this->findModel($position_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StaffPosition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $position_id Position ID
     * @return StaffPosition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($position_id)
    {
        if (($model = StaffPosition::findOne(['position_id' => $position_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
