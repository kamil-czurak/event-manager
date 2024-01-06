<?php

namespace common\models;

use common\traits\ModelMapTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "staff".
 *
 * @property int $staff_id
 * @property int|null $position_id
 * @property int|null $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int $status
 * @property string|null $comment
 * @property string|null $bid
 * @property int $created_at
 * @property int $updated_at
 *
 * @property EventTaskToStaff[] $eventTaskToStaff
 * @property StaffPosition $position
 * @property User $user
 */
class Staff extends \yii\db\ActiveRecord
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
        return 'staff';
    }

    /**
     * {@inheritdoc}
     * @return StaffQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StaffQuery(get_called_class());
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
            [['position_id', 'status', 'user_id'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 32],
            [['comment', 'bid'], 'string', 'max' => 255],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => StaffPosition::class, 'targetAttribute' => ['position_id' => 'position_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['status'], 'in', 'range' => array_keys(self::getStatusMap())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'staff_id' => 'ID',
            'position_id' => 'Stanowisko',
            'user_id' => 'Uzytkownik',
            'first_name' => 'Imie',
            'last_name' => 'Nazwisko',
            'status' => 'Status',
            'phone' => 'Telefon',
            'comment' => 'Komentarz',
            'bid' => 'Stawka',
            'created_at' => Yii::t('lbl', 'Created At'),
            'updated_at' => Yii::t('lbl', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[EventTaskToStaff]].
     *
     * @return \yii\db\ActiveQuery|EventTaskToStaffQuery
     */
    public function getEventTaskToStaff()
    {
        return $this->hasMany(EventTaskToStaff::class, ['staff_id' => 'staff_id']);
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery|StaffPositionQuery
     */
    public function getPosition()
    {
        return $this->hasOne(StaffPosition::class, ['position_id' => 'position_id']);
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery|ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

}
