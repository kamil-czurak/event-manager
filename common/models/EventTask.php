<?php

namespace common\models;

use common\traits\ModelMapTrait;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event_task".
 *
 * @property int $task_id
 * @property int $event_id
 * @property int|null $after_task_id
 * @property string $name
 * @property string|null $start_at
 * @property string $planned_start_at
 * @property string|null $finished_at
 * @property string $planned_finished_at
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property EventTask $afterTask
 * @property Event $event
 * @property EventTaskToProduct[] $eventTaskToProducts
 * @property EventTaskToStaff[] $eventTaskToStaff
 * @property EventTask[] $eventTasks
 */
class EventTask extends \yii\db\ActiveRecord
{
    use ModelMapTrait;

    public const
        STATUS_INACTIVE = 0,
        STATUS_ACTIVE = 1,
        STATUS_BLOCKED = 2,
        STATUS_TODO = 3,
        STATUS_PENDING = 4,
        STATUS_FINISHED = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_task';
    }

    public static function getStatusMap(int $status = null)
    {
        $statuses = [
            self::STATUS_INACTIVE => Yii::t('lbl', 'Inactive'),
            self::STATUS_ACTIVE => "Oczekujące",
            self::STATUS_BLOCKED => "Zablokowane",
            self::STATUS_TODO => "Możliwe do realizacji",
            self::STATUS_PENDING => "W trakcie",
            self::STATUS_FINISHED => "Zakończone",
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
            [['event_id', 'name', 'planned_start_at', 'planned_finished_at', 'status'], 'required'],
            [[ 'start_at', 'planned_start_at', 'finished_at', 'planned_finished_at',], 'safe'],
            [['event_id', 'after_task_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::class, 'targetAttribute' => ['event_id' => 'event_id']],
            [['after_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventTask::class, 'targetAttribute' => ['after_task_id' => 'task_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'ID',
            'event_id' => 'Wydarzenie',
            'after_task_id' => 'Blokujące zadanie',
            'name' => 'Nazwa',
            'start_at' => 'Rozpoczęto',
            'planned_start_at' => 'Planowane rozpoczęcie',
            'finished_at' => 'Zakończono',
            'planned_finished_at' => 'Planowane zakończenie',
            'status' => 'Status',
            'created_at' => Yii::t('lbl', 'Created At'),
            'updated_at' => Yii::t('lbl', 'Updated At'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isAttributeChanged('status')) {
                $newStatus = $this->getAttribute('status');

                if ($newStatus == self::STATUS_PENDING) {
                    $this->start_at = date('Y-m-d H:i:s', strtotime('now'));
                }

                if ($newStatus == self::STATUS_FINISHED) {
                    $this->finished_at = date('Y-m-d H:i:s', strtotime('now'));
                }

                return true;
            }
            return true;
        }
        return false;
    }
    /**
     * Gets query for [[AfterTask]].
     *
     * @return \yii\db\ActiveQuery|EventTaskQuery
     */
    public function getAfterTask()
    {
        return $this->hasOne(EventTask::class, ['task_id' => 'after_task_id']);
    }

    /**
     * Gets query for [[Event]].
     *
     * @return \yii\db\ActiveQuery|EventQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::class, ['event_id' => 'event_id']);
    }

    /**
     * Gets query for [[EventTaskToProducts]].
     *
     * @return \yii\db\ActiveQuery|EventTaskToProductQuery
     */
    public function getEventTaskToProducts()
    {
        return $this->hasMany(EventTaskToProduct::class, ['task_id' => 'task_id']);
    }

    /**
     * Gets query for [[EventTaskToStaff]].
     *
     * @return \yii\db\ActiveQuery|EventTaskToStaffQuery
     */
    public function getEventTaskToStaff()
    {
        return $this->hasMany(EventTaskToStaff::class, ['task_id' => 'task_id']);
    }

    /**
     * Gets query for [[EventTasks]].
     *
     * @return \yii\db\ActiveQuery|EventTaskQuery
     */
    public function getEventTasks()
    {
        return $this->hasMany(EventTask::class, ['after_task_id' => 'task_id']);
    }

    /**
     * {@inheritdoc}
     * @return EventTaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventTaskQuery(get_called_class());
    }
}
