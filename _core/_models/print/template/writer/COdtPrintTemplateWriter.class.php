<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:41
 */

/**
 * Загружает шаблон ODT-документа
 *
 * Class COdtPrintTemplateWriter
 */
class COdtPrintTemplateWriter implements IPrintTemplateWriter {
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
			$template = new COdtPrintTemplate($form, $object);
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
     * @throws Exception
     * @return String
     */
    public function save(IPrintTemplate $template, $filename) {
    	$template->_objZip->addFromString("content.xml", $template->_documentXML);
    	$template->_objZip->addFromString("styles.xml", $template->_styleXML);
    	 
    	// Close zip file
    	if($template->_objZip->close() === false) {
    		throw new Exception("Could not close zip file");
    	}
    	 
    	rename($template->_tempFileName, PRINT_DOCUMENTS_DIR.$filename);
    }
    
    /**
     * Удалить временный файл печатной формы
     * 
     * @param IPrintTemplate $template
     */
    public function deleteTempFile(IPrintTemplate $template) {
    	// Close zip file
    	if($template->_objZip->close() === false) {
    		throw new Exception("Could not close zip file");
    	}
    	if(file_exists($template->_tempFileName)) {
    		unlink($template->_tempFileName);
    	}
    }
}