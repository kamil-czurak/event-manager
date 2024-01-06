<?php

namespace common\models;

use yii\helpers\Url;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "file".
 *
 * @property int $file_id
 * @property string $name
 * @property string $alt
 * @property string $type
 * @property int $size
 * @property int $status
 * @property int $created_at
 */
class File extends \common\models\BaseModel
{

    const
        STATUS_PUBLIC = 1,
        STATUS_PRIVATE = 0;

    const
        MAX_NAME_LENGTH = 128,
        MAX_ALT_LENGTH = 128;


    /** @var UploadedFile */
    public $fileUpload;

    /** @var Thumbnail */
    protected $thumbnail;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     * @return FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }

    /**
     * @return string
     *
     * @throws InvalidConfigException
     */
    public static function getUploadPath(): string
    {
        if (!$uploadPath = Yii::$app->params['uploadPath'] ?? null) {
            throw new InvalidConfigException('Missing "uploadPath" app param.');
        }

        return $uploadPath;
    }

    public static function getStatusMap($status = null)
    {
        $statuses = [
            self::STATUS_PUBLIC => Yii::t('lbl', 'Public'),
            self::STATUS_PRIVATE => Yii::t('lbl', 'Private'),
        ];

        return $statuses[$status] ?? $status ?? $statuses;
    }


    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                [
                    'class' => TimestampBehavior::class,
                    'updatedAtAttribute' => false,
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fileUpload'], 'required', 'when' => function (ActiveRecord $model) {
                return $model->getIsNewRecord();
            }],
            [['fileUpload'], 'file', 'maxSize' => 32 * 1024 * 1024, 'mimeTypes' => ['image/*', 'video/*', 'application/*']], // @todo
            [['size'], 'integer'],
            [['name', 'alt'], 'trim'],
            'name-max' => [['name'], 'string', 'max' => static::MAX_NAME_LENGTH],
            'alt-max' => [['alt'], 'string', 'max' => static::MAX_ALT_LENGTH],
            [['alt'], 'default'],
            [['type'], 'string', 'max' => 128],
            'status' => [['status'], 'in', 'range' => array_keys(static::getStatusMap())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'file_id' => Yii::t('lbl', 'File ID'),
            'file' => Yii::t('lbl', 'File'),
            'fileUpload' => Yii::t('lbl', 'File'),
            'name' => Yii::t('lbl', 'Name'),
            'alt' => Yii::t('lbl', 'Alternative text'),
            'type' => Yii::t('lbl', 'Type'),
            'size' => Yii::t('lbl', 'Size'),
            'status' => Yii::t('lbl', 'Status'),
            'created_at' => Yii::t('lbl', 'Created at'),
        ];
    }

    public function attributeHints()
    {
        return array_merge(
            ['name' => Yii::t('lbl', 'Leave blank to use the default.')],
            ['alt' => Yii::t('lbl', 'Leave blank to use the default.')],
            parent::attributeHints()
        );
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $unlinked = @unlink($this->getPath());
        if (!$unlinked) {
            Yii::info('Trying to unlink non-exist file: ' . $this->getPath());
        }
    }

    /**
     * @param int|null $width
     * @param int|null $height
     * @param int|null $quality
     *
     * @return Thumbnail
     *
     * @throws Exception
     * @throws InvalidConfigException
     * @throws InvalidValueException
     */
    public function getThumbnail(int $width = null, int $height = null, int $quality = null)
    {
        if ($this->isImage() === false) {
            throw new InvalidValueException();
        }
        $this->thumbnail = (new Thumbnail($this, $width, $height, $quality));

        return $this->thumbnail;
    }

    /**
     * @param int|null $width
     * @param int|null $height
     * @param int|null $quality
     * @param bool $keepAspectRatio
     * @param bool $allowUpscaling
     *
     * @return Thumbnail
     *
     * @throws InvalidConfigException
     * @throws InvalidValueException
     * @throws \yii\base\Exception
     */
    public function getImage(int $width = null, int $height = null, int $quality = null, bool $keepAspectRatio = true, bool $allowUpscaling = false)
    {
        if ($this->isImage() === false) {
            throw new InvalidValueException();
        }
        return (new Thumbnail($this, $width, $height, $quality, $keepAspectRatio, $allowUpscaling));
    }

    /**
     * @return string
     *
     * @throws InvalidConfigException
     */
    public function getPath(): string
    {
        return self::getUploadPath() . $this->file_id;
    }

    /**
     * @param bool|string $scheme
     *
     * @return string
     */
    public function getUrl($scheme = true): string
    {
        return Url::toRoute(['/file/get', 'id' => $this->getSafeName()], $scheme);
    }

    /**
     * @param bool|string $scheme
     * @return string
     */
    public function getDownloadUrl($scheme = true): string
    {
        return Url::toRoute(['/file/download', 'id' => $this->getSafeName()], $scheme);
    }

    /**
     * Returns a string with all spaces converted to given replacement,
     * non word characters removed and the rest of characters transliterated.
     *
     * @param string $replacement The replacement to use for spaces
     * @param bool $lowercase whether to return the string in lowercase or not. Defaults to `true`.
     *
     * @return string The converted string.
     */
    public function getSafeName($replacement = '-', $lowercase = true)
    {
        $string = preg_replace('/[^.a-zA-Z0-9=\s—–-]+/u', '', $this->name);
        $string = preg_replace('/[=\s—–-]+/u', $replacement, $string);
        $string = trim($string, $replacement);

        return $this->file_id . '_' . ($lowercase ? strtolower($string) : $string);
    }

    public function getAlt(): string
    {
        return $this->alt ?? (pathinfo($this->name, PATHINFO_FILENAME) ? : $this->name);
    }

    /**
     * @return false|int
     */
    public function getMTime()
    {
        try {
            return filemtime($this->getPath());
        } catch (InvalidConfigException $e) {
            return false;
        } catch (ErrorException $e) {
            return false;
        }
    }

    public function markAsPrivate(bool $runValidation = false): bool
    {
        $this->status = self::STATUS_PRIVATE;

        return (bool) $this->update($runValidation, ['status']);
    }

    public function markAsPublic(bool $runValidation = false): bool
    {
        $this->status = self::STATUS_PUBLIC;

        return (bool) $this->update($runValidation, ['status']);
    }

    public function isImage(): bool
    {
        if (strpos($this->type, 'image/') === 0) {
            return true;
        }
        return false;
    }

    public function isVideo(): bool
    {
        if (strpos($this->type, 'video/') === 0) {
            return true;
        }
        return false;
    }

    public function isPdf(): bool
    {
        return $this->type === 'application/pdf';
    }

    public function isText(): bool
    {
        return $this->type === 'text/plain';
    }

    public function isHtml(): bool
    {
        return $this->type === 'application/xhtml+xml';
    }

    public function isPublic(): bool
    {
        return $this->status == self::STATUS_PUBLIC;
    }

    public function isPrivate(): bool
    {
        return $this->status == self::STATUS_PRIVATE;
    }

}
