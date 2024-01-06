<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[EventTaskToStaff]].
 *
 * @see EventTaskToStaff
 */
class EventTaskToStaffQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EventTaskToStaff[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EventTaskToStaff|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
