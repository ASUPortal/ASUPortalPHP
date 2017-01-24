<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:43
 */

/**
 * Загружает шаблон DOCX-документа
 *
 * Class CDocxPrintTemplateWriter
 */
class CDocxPrintTemplateWriter implements IPrintTemplateWriter {
    private $form;
    private $object;
    
    /**
     * @param CPrintForm $form
     * @param CModel $object
     */
    function __construct(CPrintForm $form, CModel $object) {
		$this->form = $form;
		$this->object = $object;
    }

    /**
     * Загрузить шаблон печатной формы
     *
     * @return IPrintTemplate
     */
    public function loadTemplate() {
		// TODO: Implement loadTemplate() method.
    }
    
    /**
     * Сохранить печатную форму
     *
     * @param IPrintTemplate $template
     * @param String filename
     * @return String
     */
    public function save(IPrintTemplate $template, $filename) {
		// TODO: Implement save() method.
    }
    
    /**
     * Удалить временный файл печатной формы
     *
     * @param IPrintTemplate $template
    */
    public function deleteTempFile(IPrintTemplate $template) {
		// TODO: Implement deleteTempFile() method.
    }

}