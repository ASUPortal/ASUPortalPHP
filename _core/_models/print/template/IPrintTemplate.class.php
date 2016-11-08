<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:29
 */

/**
 * Шаблон печатной формы
 *
 * Interface IPrintTemplate
 */
interface IPrintTemplate {
    /**
     * Получить поля из шаблона
     *
     * @return IPrintClassField[]
     */
    public function getFields();
}