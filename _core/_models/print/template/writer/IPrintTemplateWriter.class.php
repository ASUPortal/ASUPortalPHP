<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:37
 */

/**
 * Загружает/сохраняет шаблон документа
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

    /**
     * Сохранить печатную форму
     *
     * @param IPrintTemplate $template
     * @param String filename
     * @return String
     */
    public function save(IPrintTemplate $template, $filename);
    
    /**
     * Удалить временный файл печатной формы
     *
     * @param IPrintTemplate $template
     */
    public function deleteTempFile(IPrintTemplate $template);
}