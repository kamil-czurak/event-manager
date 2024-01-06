<?php

namespace common\models;

use common\traits\ModelMapTrait;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event".
 *
 * @property int $event_id
 * @property int|null $client_id
 * @property string $name
 * @property int $status
 * @property string|null $contact_coordinator_name
 * @property string|null $contact_coordinator_phone
 * @property string|null $city
 * @property string|null $street
 * @property string|null $street_number
 * @property string|null $zipcode
 * @property string|null $ready_at
 * @property string|null $start_at
 * @property string|null $end_at
 * @property string|null $comment
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Client $client
 * @property EventAttachment[] $eventAttachments
 * @property EventTask[] $eventTasks
 * @property EventTask[] $eventTasks0
 */
class Event extends \yii\db\ActiveRecord
{
    use ModelMapTrait;

    public const
        STATUS_INACTIVE = 0,
        STATUS_ACTIVE = 1,
        STATUS_PENDING = 2,
        STATUS_FINISHED = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    public static function getStatusMap(int $status = null)
    {
        $statuses = [
            self::STATUS_INACTIVE => Yii::t('lbl', 'Inactive'),
            self::STATUS_ACTIVE => Yii::t('lbl', 'Active'),
            self::STATUS_FINISHED => "Zakończone",
            self::STATUS_PENDING => "W trakcie",
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
            [['client_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'client_id'], 'required'],
            [['ready_at', 'start_at', 'end_at'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['contact_coordinator_name'], 'string', 'max' => 64],
            [['contact_coordinator_phone', 'zipcode'], 'string', 'max' => 16],
            [['city', 'street'], 'string', 'max' => 32],
            [['street_number'], 'string', 'max' => 8],
            [['comment'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'client_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'event_id' => 'ID',
            'client_id' => 'Klient',
            'name' => 'Nazwa',
            'status' => 'Status',
            'contact_coordinator_name' => 'Nazwa koordynatora',
            'contact_coordinator_phone' => 'Telefon koordynatora',
            'city' => 'Miasto',
            'street' => 'Ulica',
            'street_number' => 'Numer',
            'zipcode' => 'Kod pocztowy',
            'ready_at' => 'Gotowość na wydarzeniu',
            'start_at' => 'Początek wydarzenia',
            'end_at' => 'Koniec wydarzenia',
            'comment' => 'Komentarz',
            'created_at' => Yii::t('lbl', 'Created At'),
            'updated_at' => Yii::t('lbl','Updated At'),
        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery|ClientQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['client_id' => 'client_id']);
    }

    /**
     * Gets query for [[EventAttachments]].
     *
     * @return \yii\db\ActiveQuery|EventAttachmentQuery
     */
    public function getEventAttachments()
    {
        return $this->hasMany(EventAttachment::class, ['event_id' => 'event_id']);
    }

    /**
     * Gets query for [[EventTasks]].
     *
     * @return \yii\db\ActiveQuery|EventTaskQuery
     */
    public function getEventTasks()
    {
        return $this->hasMany(EventTask::class, ['event_id' => 'event_id']);
    }


    /**
     * {@inheritdoc}
     * @return EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventQuery(get_called_class());
    }
}
