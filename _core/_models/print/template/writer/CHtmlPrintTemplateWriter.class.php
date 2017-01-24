<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:42
 */

/**
 * Загружает шаблон HTML-документа
 *
 * Class CHtmlPrintTemplateWriter
 */
class CHtmlPrintTemplateWriter implements IPrintTemplateWriter {
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
		$form = $this->form;
		$object = $this->object;
		$file = PRINT_TEMPLATES_DIR.$form->template_file;
		if (file_exists($file)) {
			$template = new CHtmlPrintTemplate($form, $object);
			return $template;
		} else {
			throw new Exception("Файл ".$file." не найден");
		}
    }
    
    /**
     * Сохранить печатную форму
     *
     * @param IPrintTemplate $template
     * @param String filename
     * @return String
     */
    public function save(IPrintTemplate $template, $filename) {
		rename($template->_tempFileName, PRINT_DOCUMENTS_DIR.$filename);
    }
    
    /**
     * Удалить временный файл печатной формы
     * 
     * @param IPrintTemplate $template
     */
    public function deleteTempFile(IPrintTemplate $template) {
		if(file_exists($template->_tempFileName)) {
			unlink($template->_tempFileName);
		}
    }

}