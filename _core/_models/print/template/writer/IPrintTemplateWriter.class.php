<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:37
 */

/**
 * Загружает шаблон документа
 *
 * Class IPrintTemplateWriter
 */
interface IPrintTemplateWriter {
    /**
     * Загрузить шаблон печатной формы
     *
     * @return IPrintTemplate
     */
    public function loadTemplate();
    
}