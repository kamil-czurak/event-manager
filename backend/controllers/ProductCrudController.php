<?php

namespace backend\controllers;

use common\services\FileService;
use common\models\Product;
use backend\models\ProductSearch;
use common\models\ProductToFile;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProductCrudController implements the CRUD actions for Product model.
 */
class ProductCrudController extends Controller
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
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param int $product_id Product ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($product_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($product_id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $this->handleFileUpload($model, 'mainImage',ProductToFile::RELATION_MAIN_IMAGE);
            if ($model->save()) {
                return $this->redirect(['view', 'product_id' => $model->product_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $product_id Product ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($product_id)
    {
        $model = $this->findModel($product_id);


        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $this->handleFileUpload($model, 'mainImageUpload',ProductToFile::RELATION_MAIN_IMAGE);

                return $this->redirect(['view', 'product_id' => $model->product_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $product_id Product ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($product_id)
    {
        $this->findModel($product_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $product_id Product ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($product_id)
    {
        if (($model = Product::findOne(['product_id' => $product_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function handleFileUpload(Product $model, string $attribute, int $relation = ProductToFile::RELATION_DEFAULT): void
    {
        $uploadedFiles = UploadedFile::getInstances($model, $attribute);
        foreach ($uploadedFiles as $index => $uploadedFile) {
            try {
                if ($file = FileService::saveUploadedFile($uploadedFile)) {
                    $productToFile = new ProductToFile();
                    $productToFile->product_id = $model->product_id;
                    $productToFile->file_id = $file->file_id;
                    $productToFile->relation = $relation;
                    $productToFile->sequence = $index;
                    $productToFile->save();
                }
            } catch (Exception $e) {
                Yii::error($e);
            }
        }
    }
}
