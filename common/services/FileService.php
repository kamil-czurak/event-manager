<?php

namespace common\services;

use common\helpers\FileHelper;
use yii\helpers\StringHelper;
use common\models\File;
use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use Spatie\ImageOptimizer\Optimizers\Optipng;
use Spatie\ImageOptimizer\Optimizers\Pngquant;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\UploadedFile;


class FileService
{

    /**
     * Creates and return File model from uploaded file.
     *
     * @param UploadedFile|null $uploadedFile
     *
     * @return File|null
     *
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function saveUploadedFile($uploadedFile = null, int $status = File::STATUS_PUBLIC): ?File
    {
        if (!$uploadedFile) {
            return null;
        }
        if (! $uploadedFile instanceof UploadedFile) {
            throw new InvalidArgumentException('First param must be an UploadedFile instance.');
        }

        $file = self::createFileModelFromUploadedFile($uploadedFile, $status);
        if ($file->hasErrors()) {
            throw new Exception('Cannot save File model: ' . Json::encode($file->getErrors()));
        }
        if (!$uploadedFile->saveAs($file->getPath())) {
            $file->delete();
            throw new Exception('Cannot save file: ' . $file->getPath());
        }


        static::compress($file);

        return $file;
    }

    /**
     * @param string $indexName Index name in $_FILE array
     * @param string|null $fileName Save filename, uniqueid when NULL
     *
     * @return string Saved file absolute path
     *
     * @throws Exception
     */
    public static function upload(string $indexName, string $fileName = null): string
    {
        if (!$uploadedImage = UploadedFile::getInstanceByName($indexName)) {
            throw new InvalidArgumentException("Cannot handle '{$indexName}' file.");
        }
        $fileName = $fileName ? : uniqid();
        $savePath = File::getUploadPath() . $fileName;
        if (!$uploadedImage->saveAs($savePath)) {
            throw new Exception("Cannot save file: '{$savePath}'");
        }

        return $savePath;
    }

    public static function createFileModelFromUploadedFile(UploadedFile $uploadedFile, int $status = File::STATUS_PUBLIC): File
    {
        $file = Yii::createObject(File::class);
        $file->name = StringHelper::truncate($uploadedFile->name, File::MAX_NAME_LENGTH, '');
        $file->type = $uploadedFile->type;
        $file->size = $uploadedFile->size;
        $file->status = $status;
        $file->save(false);

        return $file;
    }

    /**
     * Creates File model and move $sourceFile to uploaded directory.
     *
     * @param string $sourcePath
     * @param bool $removeSource
     * @param string|null $fileName
     *
     * @return File|\common\models\File
     *
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function createFileModelFromFile(string $sourcePath, bool $removeSource = true, string $fileName = null, int $status = File::STATUS_PUBLIC): File
    {
        $sourcePath = FileHelper::normalizePath($sourcePath);
        if (is_readable($sourcePath) === false) {
            throw new Exception($sourcePath);
        }

        $file = Yii::createObject(File::class);
        $file->name = StringHelper::truncate($fileName ? : pathinfo($sourcePath, PATHINFO_BASENAME), File::MAX_NAME_LENGTH, '');
        $file->type = FileHelper::getMimeType($sourcePath);
        $file->size = filesize($sourcePath);
        $file->status = $status;
        if ($file->save(false)) {
            if ($removeSource) {
                if (rename($sourcePath, $file->getPath())) {
                    return $file;
                }
                throw new Exception("Cannot rename $sourcePath to {$file->getPath()}");
            } else {
                if (copy($sourcePath, $file->getPath())) {
                    return $file;
                }
                throw new Exception("Cannot copy $sourcePath to {$file->getPath()}");
            }
        }
        throw new Exception('Cannot save File model: ' . Json::encode($file->getAttributes()));
    }

    /**
     * @param File $file
     * @param ActiveRecord $model
     * @param string $relationName From $model
     * @param array|null $extraColumns It wil be passed to ActiveRecord::link() method
     * 
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function linkToModel(File $file, ActiveRecord $model, string $relationName, array $extraColumns = null)
    {
        if (!$model->hasProperty($relationName)) {
            throw new Exception(Yii::t('msg', '{0} don\'t have relation named {0}.', [
                $model->formName(),
                $relationName
            ]));
        }
        $relation = $model->getRelation($relationName);
        if ($relation->multiple === false) {
            $model->unlinkAll($relationName, true);
        }
        $model->link($relationName, $file, $extraColumns);
    }
    /**
     * @param File|string $source File model or path
     * @param string|null $target
     * @param int $quality Percent, 1..100
     *
     *
     * @throws \Throwable
     */
    public static function compress($source, string $target = null, int $quality = null)
    {

        try {
            if ($source instanceof File) {
                $path = $source->getPath();
            } elseif (is_string($source)) {
                $path = Yii::getAlias($source);
            } else {
                throw new InvalidArgumentException('Source must be File of filepath.');
            }
            if (is_null($target) === false) {
                $target = Yii::getAlias($target);
            }
            if (is_null($quality)) {
                $quality = 90;
            }
            if ($quality < 1 || $quality > 100) {
                throw new InvalidArgumentException('Quality must be in range 1..100.');
            }

            $optimizerChain = new OptimizerChain();
            $optimizerChain
                ->setTimeout(8)
                ->addOptimizer(new Jpegoptim([
                    "-m{$quality}",
                    '--strip-all',
                    '--all-progressive',
                ]))
                ->addOptimizer(new Pngquant([
                    '--force',
                    "--quality=65-{$quality}"
                ]))
                ->addOptimizer(new Optipng([
                    '-i0',
                    '-o2',
                    '-quiet',
                ]))
                ->optimize($path, $target)
            ;

            if ($source instanceof File) {
                $source->size = filesize($source->getPath());
                $source->update(false, ['size']);
            }

        } catch (Exception $e) {
            return $e->getMessage();
        }

        return null;
    }

    public static function duplicate(File $file)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $duplicateFile = clone $file;
            $duplicateFile->file_id = null;
            $duplicateFile->setIsNewRecord(true);

            $isSaved = $duplicateFile->save(false);

            if ($isSaved === false) {
                throw new Exception(sprintf(
                    "Duplicate for file[file_id]: %d cannot be save. Error message: %s",
                    $file->file_id,
                    Json::encode($duplicateFile->getErrors())
                ));
            }

            if (is_readable($file->getPath()) === false) {
                throw new Exception(sprintf(
                    "Physical duplicate for file[file_id]: %d cannot be create. Error message: %s.",
                    $file->file_id,
                    "File cannot be read"
                ));
            }

            $isCopied = copy($file->getPath(), $duplicateFile->getPath());

            if ($isCopied === false) {
                throw new Exception(sprintf(
                    "Physical duplicate for file[file_id]: %d cannot be create. Error message: %s",
                    $file->file_id,
                    "File could not be copied"
                ));
            }

            $transaction->commit();

        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
        }

        return null;
    }

}
