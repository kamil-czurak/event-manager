<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;


class BaseModel extends ActiveRecord
{
    const
        FLAG_ON = 1,
        FLAG_OFF = 0;


    /**
     * @param bool|null $flag
     *
     * @return string|array|bool
     */
    public static function getFlagMap(bool $flag = null)
    {
        $flags = [
            self::FLAG_ON => Yii::t('lbl', 'Yes'),
            self::FLAG_OFF => Yii::t('lbl', 'No'),
        ];

        return is_null($flag) ? $flags : $flags[$flag] ?? $flag;
    }

}