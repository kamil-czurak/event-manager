<?php

namespace backend\controllers;

use common\models\File;
use common\models\Thumbnail;
use Yii;
use yii\base\Controller;
use yii\filters\HttpCache;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;


class FileController extends Controller
{

    /** @var File */
    protected static $file;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return
            [
                [
                    'class' => HttpCache::class,
                    'only' => ['get', 'get-thumbnail'],
                    'cacheControlHeader' => 'max-age=2592000, public',
                    'lastModified' => function ($action, $params) {
                        $file = $this->getFileFromAction($action);

                        return $file ? $file->getMTime() : null;
                    },
                    'etagSeed' => function ($action, $params) {
                        if (!$file = $this->getFileFromAction($action)) {
                            return null;
                        }
                        if (!$mTime = $file->getMTime()) {
                            return null;
                        }
                        $seed = "f{$file->file_id}m{$mTime}";
                        if ($action->id === 'get-thumbnail' && $file->isImage()) {
                            $req = Yii::$app->request;
                            $seed .= "w{$req->get('w')}h{$req->get('h')}q{$req->get('q')}a{$req->get('a', false)}u{$req->get('u', false)}";
                        }
                        return $seed;
                    },
                ],
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }



    /**
     * @param int $id
     *
     * @return \yii\console\Response|\yii\web\Response
     *
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id = null)
    {
        $id = (int)Yii::$app->request->get('id');
        if ($file = File::find()->byId($id)->one()) {
            if (!$file->delete()) {
                throw new ServerErrorHttpException('Cannot delete file.');
            }

            return Yii::$app->response->setStatusCode(204);
        }
        throw new NotFoundHttpException();
    }

    /**
     * @param $id
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGet($id = null)
    {
        $id = (int)Yii::$app->request->get('id');
        return $this->getSendFileResponse($id, true);
    }

    /**
     * @param $name
     * @param int|null $w Width
     * @param int|null $h Height
     * @param int|null $q Quality
     * @param bool $a Keep Aspect ratio
     * @param bool $u Allow Up scaling
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function actionGetThumbnail($name = null, int $w = null, int $h = null, int $q = null, bool $a = false, bool $u = false)
    {
        $name = (int)Yii::$app->request->get('name');
        if (!$file = File::find()->byId($name)->one()) {
            throw new NotFoundHttpException();
        }
        try {
            $thumbnail = new Thumbnail($file, $w, $h, $q, $a, $u);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
        $path = $thumbnail->getPath();
        $fileName = basename($path);

        $sendFileArgs = [
            $path,
            $fileName,
            [
                'mimeType' => FileHelper::getMimeTypeByExtension($path),
                'fileSize' => filesize($path),
            ]
        ];

        return Yii::$app->response->sendFile(...$sendFileArgs);
    }


    /**
     * @param int|string $fileName
     * @param bool $inline
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    protected function getSendFileResponse($fileName, bool $inline = false)
    {
        $fileId = (int) $fileName;
        if ($file = File::find()->byId($fileId)->one()) {
            $path = $file->getPath();
            $mimeType = $file->type;
            $size = $file->size;
            if (!is_readable($path)) {
                if ($file->isImage()) {
                    $path = Yii::getAlias('@common/assets/img/no-image.png');
                    $mimeType = null;
                    $size = filesize($path);
                } else {
                    throw new NotFoundHttpException("File {$fileId} was not found.");
                }
            }

            $sendFileArgs = [
                $path,
                $file->name,
                [
                    'mimeType' => $mimeType,
                    'fileSize' => $size,
                    'inline' => $inline,
                ],
            ];

            return Yii::$app->response->sendFile(...$sendFileArgs);
        }

        throw new NotFoundHttpException("File {$fileId} was not found.");
    }

    protected function getFileFromAction($action): ?File
    {
        if ($action->id === 'get-thumbnail') {
            $fileId = (int)Yii::$app->request->get('name');
        } else {
            $fileId = (int)Yii::$app->request->get('id');
        }
        if (!self::$file || self::$file->file_id != $fileId) {
            self::$file = File::findOne($fileId);
        }

        return self::$file;
    }
}
