<?php

namespace common\traits;

use common\models\File;
use common\services\FileService;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\UserException;
use yii\db\ActiveQuery;
use yii\web\UploadedFile;


trait ModelToFileTrait
{
    /**
     * @param int|string $relationOrColumn For 1:n relation should be int (relation const), for 1:1 relation it's column name with File id, default 'file_id'.
     *
     * @return ActiveQuery
     */
    public function getFile($relationOrColumn = null)
    {
        $relationCallable = null;
        if (is_null($relationOrColumn) === false) {
            if (is_int($relationOrColumn)) {
                $relationCallable = function (ActiveQuery $query) use ($relationOrColumn) {
                    return $query->where(['relation' => $relationOrColumn]);
                };
            }
            if (is_string($relationOrColumn)) {
                return $this->hasOne(File::class, ['file_id' => $relationOrColumn]);
            }
        }

        if (defined('self::RELATION_TO_FILES_NAME')) {
            return $this->hasOne(File::class, ['file_id' => 'file_id'])->via(self::RELATION_TO_FILES_NAME, $relationCallable);
        }

        return $this->hasOne(File::class, ['file_id' => 'file_id']);
    }

    /**
     * @param int $relation
     *
     * @return ActiveQuery
     */
    public function getFiles(int $relation = null)
    {
        if (defined('self::RELATION_TO_FILES_NAME') === false) {
            throw new InvalidConfigException('RELATION_TO_FILES_NAME const must be set for 1:n relations.');
        }
        $relationCallable = null;
        if (is_null($relation) === false) {
            $relationCallable = function (ActiveQuery $query) use ($relation) {
                return $query->where(['relation' => $relation]);
            };
        }

        return $this->hasMany(File::class, ['file_id' => 'file_id'])->via(self::RELATION_TO_FILES_NAME, $relationCallable);
    }


    protected function attachFileUploadEvent(string $attributeName, string $relationName, int $relationConst = null, bool $multiple = false, array $extraColumns = []): void
    {
        if ($multiple) {
            $fileUpload = UploadedFile::getInstances($this, $attributeName);
        } else {
            $fileUpload = UploadedFile::getInstance($this, $attributeName);
        }

        // attach file to validation before model save
        $this->on(self::EVENT_BEFORE_VALIDATE, function ($event) use ($fileUpload, $attributeName) {
            $this->$attributeName = $fileUpload;
        });

        $this->on(self::EVENT_AFTER_INSERT, function ($event) use ($fileUpload, $relationName, $relationConst, $extraColumns) {
            $this->handleFilesUpload(
                $fileUpload,
                $relationName,
                $relationConst,
                $extraColumns
            );
        });

        $this->on(self::EVENT_AFTER_UPDATE, function ($event) use ($fileUpload, $relationName, $relationConst, $extraColumns) {
            $this->handleFilesUpload(
                $fileUpload,
                $relationName,
                $relationConst,
                $extraColumns
            );
        });
    }

    /**
     * @param UploadedFile|UploadedFile[] $uploadedFiles
     * @param string $modelRelationName
     * @param integer|null $fileRelation Leave null for 1:1 relation
     *
     * @throws UserException
     */
    protected function handleFilesUpload($uploadedFiles, string $modelRelationName, int $fileRelation = null, array $extraColumns = [])
    {
        if (!$uploadedFiles) {
            return;
        }
        if (!is_array($uploadedFiles)) {
            $uploadedFiles = [$uploadedFiles];
        }


        try {
            $fileService = Yii::createObject(FileService::class);
            foreach ($uploadedFiles as $uploadedFile) {
                if ($file = $fileService::saveUploadedFile($uploadedFile)) {

                    // link model with File
                    if (is_null($fileRelation) === false) {
                        // relation one-to-many (junction table)
                        $fileService::linkToModel($file, $this, $modelRelationName, array_merge(['relation' => $fileRelation], $extraColumns));
                    } else {
                        // relation one-to-one (no junction table)
                        if (!$relation = $this->getRelation($modelRelationName)) {
                            throw new InvalidConfigException('Model ' . self::class . " dont't have relation '{$modelRelationName}'.");
                        }
                        $fileRelatedColumnName = current($relation->link);
                        if ($this->hasAttribute($fileRelatedColumnName) === false) {
                            throw new InvalidConfigException('Model ' . self::class . " dont't have attribute '{$fileRelatedColumnName}'.");
                        }

                        $conditions = [];
                        if (is_array($this->getPrimaryKey())) {
                            foreach ($this->getPrimaryKey() as $columnName => $value) {
                                $conditions[] = "`{$columnName}` = '{$value}'";
                            }
                        } else {
                            $primaryKeyColumnName = current(self::primaryKey());
                            if (is_int($this->getPrimaryKey())) {
                                $conditions[] = "`{$primaryKeyColumnName}` = {$this->getPrimaryKey()}";
                            } else {
                                $conditions[] = "`{$primaryKeyColumnName}` = '{$this->getPrimaryKey()}'";
                            }
                        }
                        $pkCondition = implode(' AND ', $conditions);

                        $updated = Yii::$app->db->createCommand("UPDATE `{$this::tableName()}` SET `{$fileRelatedColumnName}` = '{$file->file_id}' WHERE {$pkCondition}")->execute();
                    }
                }
            };

        } catch (\Throwable $e) {
            Yii::error($e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            throw new UserException(Yii::t('msg', 'Cannot upload: {0}.', $uploadedFile->name . $e->getMessage()));
        }
    }

}
