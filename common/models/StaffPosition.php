<?php

namespace common\models;

use common\traits\ModelMapTrait;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "staff_position".
 *
 * @property int $position_id
 * @property string $name
 * @property int $status
 * @property int $updated_at
 * @property int $created_at
 *
 * @property Staff[] $staff
 */
class StaffPosition extends \yii\db\ActiveRecord
{
    use ModelMapTrait;


    public const
        STATUS_INACTIVE = 0,
        STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     * @return StaffPositionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StaffPositionQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_position';
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
            [['status', 'updated_at', 'created_at'], 'integer'],
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
            'position_id' => 'ID',
            'name' => 'Nazwa',
            'status' => 'Status',
            'updated_at' => Yii::t('lbl', 'Updated At'),
            'created_at' => Yii::t('lbl', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Staff]].
     *
     * @return \yii\db\ActiveQuery|StaffQuery
     */
    public function getStaff()
    {
        return $this->hasMany(Staff::class, ['position_id' => 'position_id']);
    }

}
