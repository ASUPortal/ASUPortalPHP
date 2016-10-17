<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:42
 */

class CHtmlPrintTemplateWriter implements IPrintTemplateWriter {
    private $form;

    function __construct($form) {
        $this->form = $form;
    }

    /**
     * Загрузить шаблон печатной формы
     *
     * @return IPrintTemplate
     */
    public function loadTemplate() {
        return new CHtmlPrintTemplate($this->form);
    }


}