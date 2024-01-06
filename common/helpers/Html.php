<?php

namespace common\helpers;

use common\models\File;
use yii\helpers\StringHelper;


class Html extends \yii\bootstrap\Html
{

    public static function getFilePreview(File $file = null, bool $linkToSource = true, int $width = 120, int $height = 120, int $nameLengthLimit = 32): ?string
    {
        if (is_null($file)) {
            return null;
        }

        if ($file->isImage()) {
            $content = Html::img($file->getThumbnail($width, $height)->getUrl());
        } else {
            $content = StringHelper::truncate($file->name, $nameLengthLimit);
        }
        if ($linkToSource) {
            return Html::a($content, ['/file/get', 'id' => $file->file_id]);
        }

        return $content;
    }

    public static function getFilesPreview(array $files = null, bool $linkToSource = true, int $width = 120, int $height = 120): array
    {
        if (empty($files)) {
            return [];
        }
        $collection = [];
        foreach ($files as $file) {
            $collection[] = self::getFilePreview($file, $linkToSource, $width, $height);
        }
        return $collection;
    }

}
