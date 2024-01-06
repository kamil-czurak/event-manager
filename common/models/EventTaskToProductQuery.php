<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[EventTaskToProduct]].
 *
 * @see EventTaskToProduct
 */
class EventTaskToProductQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EventTaskToProduct[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EventTaskToProduct|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
