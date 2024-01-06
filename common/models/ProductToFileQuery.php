<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ProductToFile]].
 *
 * @see ProductToFile
 */
class ProductToFileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ProductToFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProductToFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
