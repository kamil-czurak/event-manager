<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product_to_file".
 *
 * @property int $product_id
 * @property int $file_id
 * @property int $relation
 * @property int $sequence
 * @property int $created_at
 * @property int $updated_at
 *
 * @property File $file
 * @property Product $product
 */
class ProductToFile extends \yii\db\ActiveRecord
{
    const RELATION_DEFAULT = 1;
    const RELATION_MAIN_IMAGE = 2;
    const RELATION_SECONDARY_IMAGE = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_to_file';
    }

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                TimestampBehavior::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'file_id', 'relation'], 'required'],
            [['product_id', 'file_id', 'relation', 'sequence', 'created_at', 'updated_at'], 'integer'],
            [['product_id', 'file_id', 'relation'], 'unique', 'targetAttribute' => ['product_id', 'file_id', 'relation']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'product_id']],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['file_id' => 'file_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'file_id' => 'File ID',
            'relation' => 'Relation',
            'sequence' => 'Sequence',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[File]].
     *
     * @return \yii\db\ActiveQuery|FileQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::class, ['file_id' => 'file_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['product_id' => 'product_id']);
    }

    /**
     * {@inheritdoc}
     * @return ProductToFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductToFileQuery(get_called_class());
    }
}
