<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:47
 */

/**
 * Поле шаблона печатной формы
 *
 * Interface IPrintTemplateField
 */
interface IPrintTemplateField {
    /**
     * Название поля
     *
     * @return String
     */
    public function getName();

    /**
     * Установить значение поля
     *
     * @param $value
     */
    public function setValue($value);
}