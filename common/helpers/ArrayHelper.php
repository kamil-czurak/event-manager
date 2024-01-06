<?php

namespace common\helpers;


class ArrayHelper extends \yii\helpers\ArrayHelper
{

    public static function isKeyExistsInMultidimensionalArray(array $array, string $key): bool
    {
        if (array_key_exists($key, $array)) {
            return true;
        }
        foreach ($array as $element) {
            if (is_array($element)) {
                if (self::isKeyExistsInMultidimensionalArray($element, $key)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Filter array against null or '' values.
     *
     * @param array $array
     *
     * @return array
     */
    public static function filterEmptyValues(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::filterEmptyValues($value);
            } else {
                if (is_null($value) || $value === '') {
                    unset($array[$key]);
                }
            }
        }

        return $array;
    }

    public static function castValuesToInt($array): array
    {
        if (is_array($array) === false) {
            $array = [$array];
        }
        array_walk($array, function (&$value) {
            $value = (int) $value;
        });

        return $array;
    }

    public static function hasDuplicates(array $array): bool
    {
        return count($array) !== count(array_flip($array));
    }

}
