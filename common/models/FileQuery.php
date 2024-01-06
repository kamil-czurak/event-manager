<?php

namespace common\models;

use common\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[File]].
 *
 * @see File
 */
class FileQuery extends \yii\db\ActiveQuery
{

    public function byId($ids): self
    {
        $ids = ArrayHelper::castValuesToInt($ids);

        return $this->andWhere(['file_id' => $ids]);
    }

    public function typeImage(): self
    {
        return $this->andWhere(['like', 'type', 'image/%', false]);
    }

    public function typePdf(): self
    {
        return $this->andWhere(['type' => 'application/pdf']);
    }

    public function private(): self
    {
        return $this->andWhere(['status' => File::STATUS_PRIVATE]);
    }

    public function public(): self
    {
        return $this->andWhere(['status' => File::STATUS_PUBLIC]);
    }

    /**
     * {@inheritdoc}
     * @return File[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     */
    public function one($db = null): ?File
    {
        return parent::one($db);
    }
}
