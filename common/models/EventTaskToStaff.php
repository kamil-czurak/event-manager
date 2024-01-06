<?php

namespace common\models;

use common\services\EventService;
use common\services\ProductService;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event_task_to_staff".
 *
 * @property int $task_to_staff_id
 * @property int $task_id
 * @property int $staff_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Staff $staff
 * @property EventTask $task
 */
class EventTaskToStaff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_task_to_staff';
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
            [['task_id', 'staff_id'], 'required'],
            [['task_id', 'staff_id', 'created_at', 'updated_at'], 'integer'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventTask::class, 'targetAttribute' => ['task_id' => 'task_id']],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::class, 'targetAttribute' => ['staff_id' => 'staff_id']],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::class, 'targetAttribute' => ['staff_id' => 'staff_id']],
            [['staff_id', 'task_id'], 'unique', 'targetAttribute' => ['staff_id', 'task_id']],
            [['staff_id'], 'validateStaff']
        ];
    }

    public function validateStaff($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (EventService::getStaffInTime($this->staff_id, $this->task)) {
                $this->addError($attribute, 'Dany pracownik jest przydzielony do innego zadania w tym czasie.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'task_to_staff_id' => 'ID',
            'task_id' => 'Zadanie',
            'staff_id' => 'Pracownik',
            'created_at' => Yii::t('lbl', 'Created At'),
            'updated_at' => Yii::t('lbl', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Staff]].
     *
     * @return \yii\db\ActiveQuery|StaffQuery
     */
    public function getStaff()
    {
        return $this->hasOne(Staff::class, ['staff_id' => 'staff_id']);
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
     * {@inheritdoc}
     * @return EventTaskToStaffQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventTaskToStaffQuery(get_called_class());
    }
}
