<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[StaffPosition]].
 *
 * @see StaffPosition
 */
class StaffPositionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return StaffPosition[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return StaffPosition|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
