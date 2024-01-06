<?php

namespace common\helpers;


class FileHelper extends \yii\helpers\FileHelper
{

    public static function isImage(string $path): bool
    {
        if ($mime = static::getMimeType($path)) {
            if (strpos($mime, 'image/') === 0) {
                return true;
            }
        }
        return false;
    }

    public static function getLinesCount(string $path): ?int
    {
        if (!$mime = self::getMimeType($path)) {
            return null;
        }

        if (strpos($mime, 'text/') === false && $mime != 'application/csv') {
            return null;
        }

        [$linesCount] = explode(' ', exec("wc -l {$path}"));

        return $linesCount;
    }

    public static function isEmptyDirectory(string $path): bool
    {
        if (is_dir($path) === false) {
            return false;
        }

        return !(new \FilesystemIterator($path))->valid();
    }

}
