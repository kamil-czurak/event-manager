<?php

namespace backend\controllers;

use common\models\EventAttachment;
use backend\models\EventAttachmentSearch;
use common\models\ProductToFile;
use common\services\FileService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * EventAttachmentCrudController implements the CRUD actions for EventAttachment model.
 */
class EventAttachmentCrudController extends Controller
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
     * Lists all EventAttachment models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EventAttachmentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EventAttachment model.
     * @param int $attachment_id Attachment ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($attachment_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($attachment_id),
        ]);
    }

    /**
     * Creates a new EventAttachment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate(int $event_id)
    {
        $model = new EventAttachment();

        $model->event_id = $event_id;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->file_id = $this->handleFileUpload($model, 'fileUpload');
            if ($model->save()) {
                return $this->redirect(['/event-crud/view', 'event_id' => $model->event_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EventAttachment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $attachment_id Attachment ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($attachment_id)
    {
        $model = $this->findModel($attachment_id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $model->file_id = $this->handleFileUpload($model, 'fileUpload');
            }

            return $this->redirect(['/event-crud/view', 'event_id' => $model->event_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EventAttachment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $attachment_id Attachment ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($attachment_id)
    {
        $model = $this->findModel($attachment_id);
        $event_id = $model->event_id;
        $model->delete();

        return $this->redirect(['/event-crud/view', 'event_id' => $event_id]);
    }

    /**
     * Finds the EventAttachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $attachment_id Attachment ID
     * @return EventAttachment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($attachment_id)
    {
        if (($model = EventAttachment::findOne(['attachment_id' => $attachment_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function handleFileUpload(EventAttachment $model, string $attribute): ?int
    {
        $uploadedFiles = UploadedFile::getInstances($model, $attribute);
        foreach ($uploadedFiles as $index => $uploadedFile) {
            try {
                if ($file = FileService::saveUploadedFile($uploadedFile)) {
                    return $file->file_id;
                }
            } catch (Exception $e) {
                Yii::error($e);
            }
        }

        return null;
    }
}
