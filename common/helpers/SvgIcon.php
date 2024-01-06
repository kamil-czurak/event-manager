<?php

namespace common\helpers;

use InlineSvg\Collection;
use InlineSvg\Svg;
use InlineSvg\Transformers\Cleaner;
use Yii;

class SvgIcon
{
    private static ?Collection $icons = null;


    public static function print(string $name): ?Svg
    {
        if (is_null(static::$icons)) {
            static::$icons = Collection::fromPath(Yii::getAlias('@backend') . "/web/img/svg");
            static::$icons->addTransformer(new Cleaner());
        }

        return static::$icons->get($name);
    }

}
