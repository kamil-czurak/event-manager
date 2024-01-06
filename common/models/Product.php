<?php

namespace common\models;

use common\traits\ModelMapTrait;
use common\traits\ModelToFileTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property int $product_id
 * @property int $category_id
 * @property string $name
 * @property int $status
 * @property float|null $quantity
 * @property string|null $comment
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ProductCategory $category
 * @property ProductToFile[] $productToFiles
 * @property File $mainImage
 */
class Product extends \yii\db\ActiveRecord
{
    use ModelMapTrait;
    use ModelToFileTrait;

    public const
        STATUS_INACTIVE = 0,
        STATUS_ACTIVE = 1;

    /** @var UploadedFile */
    public $mainImageUpload;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    public static function getStatusMap(int $status = null)
    {
        $statuses = [
            self::STATUS_INACTIVE => Yii::t('lbl', 'Inactive'),
            self::STATUS_ACTIVE => Yii::t('lbl', 'Active'),
        ];

        return $statuses[$status] ?? $status ?? $statuses;
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
            [['category_id', 'name'], 'required'],
            [['category_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['quantity'], 'number'],
            [['name'], 'string', 'max' => 128],
            [['comment'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::class, 'targetAttribute' => ['category_id' => 'category_id']],
            [['mainImageUpload'], 'image', 'maxSize' => 8 * 1024 * 1024],
            //[['mainImageUpload'], 'required', 'when' => function () { return empty($this->mainImageUpload); }, 'whenClient' => 'function() { return false; }'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'ID',
            'category_id' => 'Kategoria',
            'name' => 'Nazwa',
            'status' => 'Status',
            'quantity' => 'Ilość',
            'comment' => 'Komentarz',
            'created_at' => Yii::t('lbl', 'Created At'),
            'updated_at' => Yii::t('lbl' , 'Updated At'),

            'mainImageUpload' => 'Zdjęcie',
            'mainImage' => 'Zdjęcie',
        ];
    }


    public function getMainImage(): ?File
    {
        $productToFile = ProductToFile::find()->andWhere(['product_id' => $this->product_id])->andWhere(['relation' => ProductToFile::RELATION_MAIN_IMAGE])->one();
        return File::find()->byId($productToFile->file_id ?? 0)->one();
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|ProductCategoryQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductCategory::class, ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[ProductToFiles]].
     *
     * @return \yii\db\ActiveQuery|ProductToFileQuery
     */
    public function getProductToFiles()
    {
        return $this->hasMany(ProductToFile::class, ['product_id' => 'product_id']);
    }


    /**
     * {@inheritdoc}
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }
}
