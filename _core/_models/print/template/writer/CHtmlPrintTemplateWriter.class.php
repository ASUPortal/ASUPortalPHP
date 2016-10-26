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
    function __construct($form, $object) {
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

}