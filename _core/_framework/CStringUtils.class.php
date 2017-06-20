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
    
    /**
     * Пустая ли строка с html-тегами
     *
     * @param $string
     * @return bool
     */
    public static function isEmptyWithTags($string){
        $value = html_entity_decode(strip_tags($string));
        return empty($value) or $value == "";
    }
    
    /**
     * Преобразовать дробное число с учетом направления отображения (browse, [mysql, php, ...])
     * $number - число для преобразования
     * $direction - направление преобразования
     * пример: numLocal('12.51','browse') -> '12,51'
     * пример: numLocal('12,51','sql') -> '12.51'
     */
    public static function numLocal($number, $direction='browse') {
        $localnum = '';
        
        // очистить нулевые значения
        if (floatval(str_replace(',','.',$number)) == 0) {
            $number = '';	
        }
        if ($number!='') {
            if ($direction == 'browse') {
                $localnum = str_replace('.',',',$number);
            } else {
                $localnum = str_replace(',','.',$number);
            }
        }
        return $localnum;
    }
}