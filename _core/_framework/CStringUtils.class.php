<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:33
 */

class CStringUtils {
    /**
     * @param $string1
     * @param $string2
     * @return bool
     */
    public static function equalsIgnoreCase($string1, $string2) {
        return mb_strtolower($string1) == mb_strtolower($string2);
    }

    /**
     * Пустая ли строка
     *
     * @param $string
     * @return bool
     */
    public static function isBlank($string) {
        return mb_strlen(trim($string)) == 0;
    }
}