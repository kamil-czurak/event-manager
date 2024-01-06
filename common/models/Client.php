<?php

namespace common\models;

use common\traits\ModelMapTrait;
use yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "client".
 *
 * @property int $client_id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Event[] $events
 */
class Client extends \yii\db\ActiveRecord
{

    use ModelMapTrait;
    public const
        STATUS_INACTIVE = 0,
        STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * {@inheritdoc}
     * @return ClientQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClientQuery(get_called_class());
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
            [['name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['status'], 'in', 'range' => array_keys(self::getStatusMap())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'client_id' => 'ID',
            'name' => 'Nazwa',
            'status' => 'Status',
            'created_at' => Yii::t('lbl', 'Created At'),
            'updated_at' => Yii::t('lbl', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Events]].
     *
     * @return \yii\db\ActiveQuery|EventQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::class, ['client_id' => 'client_id']);
    }

}
