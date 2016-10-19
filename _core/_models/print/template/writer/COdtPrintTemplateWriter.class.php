<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:41
 */

class COdtPrintTemplateWriter implements IPrintTemplateWriter {
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
        new COdtPrintTemplate($this->form);
    }

    /**
     * Сохранить печатную форму
     *
     * @param IPrintTemplate $template
     * @param String filename
     * @throws Exception
     * @return String
     */
    public function save(IPrintTemplate $template, $filename) {
        /**
         * Мы знаем, что в loadTemplate сделали получение объекта типа
         * COdtPrintTemplate, значит и $template здесь того же типа.
         * Теперь, зная специфику COdtPrintTemplate сохраняем его
         */

//        if(file_exists($filename)) {
//            unlink($filename);
//        }
//
//        $this->_objZip->addFromString('content.xml', $this->_documentXML);
//        $this->_objZip->addFromString('styles.xml', $this->_styleXML);
//
//        // Close zip file
//        if($this->_objZip->close() === false) {
//            throw new Exception('Could not close zip file.');
//        }
//
//        rename($this->_tempFileName, $filename);
    }


}