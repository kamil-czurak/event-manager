<?php

namespace common\models;

use common\services\ProductService;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event_task_to_product".
 *
 * @property int $event_task_to_product_id
 * @property int $task_id
 * @property int $product_id
 * @property int|null $quantity
 * @property int $created_at
 * @property int $updated_at
 *
 * @property EventTask $task
 * @property Product $product
 */
class EventTaskToProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_task_to_product';
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
            [['task_id', 'product_id'], 'required'],
            [['task_id', 'product_id', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['task_id', 'product_id'], 'unique', 'targetAttribute' => ['task_id', 'product_id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventTask::class, 'targetAttribute' => ['task_id' => 'task_id']],
            [['quantity'], 'validateQuantity']
        ];
    }

    public function validateQuantity($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->quantity > ProductService::getQuantityInTime($this->product, $this->task)) {
                $this->addError($attribute, 'Brak takiej ilości na stanie w tym czasie.');
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'event_task_to_product_id' => 'ID',
            'task_id' => 'Task ID',
            'product_id' => 'Produkt',
            'quantity' => 'Ilość',
            'created_at' => Yii::t('lbl', 'Created At'),
            'updated_at' => Yii::t('lbl','Updated At'),
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery|EventTaskQuery
     */
    public function getTask()
    {
        return $this->hasOne(EventTask::class, ['task_id' => 'task_id']);
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
     * @return EventTaskToProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventTaskToProductQuery(get_called_class());
    }
}
