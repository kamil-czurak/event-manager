<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "event_attachment".
 *
 * @property int $attachment_id
 * @property int $event_id
 * @property int $file_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Event $event
 * @property File $file
 */
class EventAttachment extends \yii\db\ActiveRecord
{

    /** @var UploadedFile */
    public $fileUpload;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_attachment';
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
            [['event_id'], 'required'],
            [['event_id', 'file_id', 'created_at', 'updated_at'], 'integer'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::class, 'targetAttribute' => ['event_id' => 'event_id']],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['file_id' => 'file_id']],
            [['fileUpload'], 'file', 'maxSize' => 8 * 1024 * 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attachment_id' => 'ID',
            'event_id' => 'Wydarzenie',
            'file_id' => 'Plik',
            'created_at' => Yii::t('lbl', 'Created At'),
            'updated_at' => Yii::t('lbl', 'Updated At'),

            'fileUpload' => 'Plik'
        ];
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
     * Gets query for [[File]].
     *
     * @return \yii\db\ActiveQuery|FileQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::class, ['file_id' => 'file_id']);
    }

    /**
     * {@inheritdoc}
     * @return EventAttachmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventAttachmentQuery(get_called_class());
    }
}
