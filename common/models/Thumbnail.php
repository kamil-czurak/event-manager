<?php

namespace common\models;

use common\helpers\FileHelper;
use common\services\FileService;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\imagine\Image;


class Thumbnail
{
    const
        DEFAULT_THUMBNAIL_WIDTH = 120,
        DEFAULT_THUMBNAIL_HEIGHT = 120,
        DEFAULT_THUMBNAIL_QUALITY = 92;

    protected
        $width,
        $height,
        $quality,
        $keepAspectRatio,
        $allowUpscaling,
        $name,
        $sourceImagePath,
        $extension;

    protected static $placeholderPath = null;


    /**
     * @param string|File $source Source image path of File instance
     * @param int $width
     * @param int $height
     * @param int $quality
     * @param bool $keepAspectRatio
     * @param bool $allowUpscaling
     *
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function __construct($source, int $width = null, int $height = null, int $quality = null, $keepAspectRatio = false, $allowUpscaling = false)
    {
        if ($source instanceof File) {
            $this->name = $source->file_id;
            $this->sourceImagePath = $source->getPath();
        } else {
            $this->name = basename($source);
            $this->sourceImagePath = $source;
        }

        if (!is_readable($this->sourceImagePath)) {
            Yii::error("Source file '{$this->sourceImagePath}' is not readable.");
            $this->sourceImagePath = self::getPlaceholderPath();
        }

        $this->width = $width ?? self::DEFAULT_THUMBNAIL_WIDTH;
        $this->height = $height ?? self::DEFAULT_THUMBNAIL_HEIGHT;
        $this->quality = $quality ?? self::DEFAULT_THUMBNAIL_QUALITY;
        $this->keepAspectRatio = $keepAspectRatio;
        $this->allowUpscaling = $allowUpscaling;
        $this->extension = $this->getExtension();

        if (!file_exists($this->getPath())) {
            $this->generate();
        }
    }


    public static function getBasePath(): string
    {
        return Yii::$app->params['thumbnailPath'];
    }

    public static function getPlaceholderPath(): string
    {
        if (empty(self::$placeholderPath)) {
            self::$placeholderPath = Yii::getAlias('@common/assets/img/no-image.png');
        }

        return self::$placeholderPath;
    }

    public static function setPlaceholderPath(string $path): void
    {
        static::$placeholderPath = $path;
    }


    public function getPath(): string
    {
        return self::getBasePath() . "{$this->name}w{$this->width}h{$this->height}q{$this->quality}a{$this->keepAspectRatio}u{$this->allowUpscaling}.{$this->extension}";
    }

    /**
     * @return $this
     */
    public function generate()
    {
        try {
            if ($this->keepAspectRatio) {
                $image = Image::resize($this->sourceImagePath, $this->width, $this->height, $this->keepAspectRatio, $this->allowUpscaling);
            } else {
                $image = Image::thumbnail($this->sourceImagePath, $this->width, $this->height);
            }
            $image->save($this->getPath(), ['quality' => $this->quality]);

            FileService::compress($this->getPath());

        } catch (\Imagine\Exception\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        } catch (\Imagine\Exception\NotSupportedException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        return $this;
    }

    public function getUrl(bool $withSchema = true): string
    {
        $routeArray = [
            '/file/get-thumbnail',
            'name' => $this->name,
            'w' => $this->width,
            'h' => $this->height,
            'q' => $this->quality,
        ];
        if ($this->keepAspectRatio) {
            $routeArray['a'] = $this->keepAspectRatio;
        }
        if ($this->allowUpscaling) {
            $routeArray['u'] = $this->allowUpscaling;
        }

        return Url::toRoute($routeArray, $withSchema);
    }


    /**
     * @return string
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function getExtension(): string
    {
        if (!$this->sourceImagePath) {
            throw new InvalidConfigException(Yii::t('msg', 'Missing source image path.'));
        }
        $extensions = FileHelper::getExtensionsByMimeType(FileHelper::getMimeType($this->sourceImagePath));
        if (empty($extensions)) {
            throw new InvalidConfigException(Yii::t('msg', "Cannot get extension from '{$this->sourceImagePath}'."));
        }
        $this->extension = end($extensions);

        return $this->extension;
    }

}
