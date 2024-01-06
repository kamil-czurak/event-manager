<?php

namespace common\traits;

use common\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\caching\DbDependency;
use yii\caching\Dependency;
use yii\db\ActiveRecord;
use yii\db\ExpressionInterface;


trait ModelMapTrait
{

    protected static $modelMapValueColumn;


    /**
     * @param string|\Closure $valueColumn
     * @param string|array|ExpressionInterface $whereCondition
     *
     * @return array
     *
     * @throws InvalidConfigException
     */
    public static function getMap($valueColumn = null, $whereCondition = null): array
    {
        if (is_null($valueColumn)) {
            $valueColumn = static::getModelMapValueColumn();
        }
        $primaryKey = current(self::primaryKey());
        $query = self::find();
        if ($whereCondition) {
            $query->where($whereCondition);
        }
        $array = ArrayHelper::map(
                $query
                ->asArray()
                ->cache(static::getModelMapCacheTime(), static::getModelMapCacheDependency())
                ->all(),
            $primaryKey,
            $valueColumn
        );

        return $array;
    }


    /**
     * @return int Cache time in seconds
     */
    protected static function getModelMapCacheTime(): int
    {
        return 3600;
    }

    /**
     * @return string|\Closure "Name" column
     *
     * @throws InvalidConfigException
     */
    protected static function getModelMapValueColumn()
    {
        if (empty(self::$modelMapValueColumn)) {
            /** @var ActiveRecord $model */
            $model = new self;
            if ($model->hasAttribute('system_name')) {
                self::$modelMapValueColumn = 'system_name';
            } elseif ($model->hasAttribute('name')) {
                self::$modelMapValueColumn = 'name';
            } elseif ($model->hasAttribute('title')) {
                self::$modelMapValueColumn = 'title';
            } else {
                throw new InvalidConfigException('ModelMapValueColumn was not configured.');
            }
        }

        return self::$modelMapValueColumn;
    }

    protected static function getModelMapCacheDependency(): Dependency
    {
        return new DbDependency(['sql' => 'SELECT max(updated_at) FROM ' . self::tableName()]);
    }

}
