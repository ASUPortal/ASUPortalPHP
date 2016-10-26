<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:40
 */

/**
 * Шаблон печатной формы на основе HTML-документа
 *
 * Class CHtmlPrintTemplate
 */
class CHtmlPrintTemplate extends CBaseController implements IPrintTemplate {
	private $form;
	private $object;
	public $file;
	public $_tempFileName;

	/**
	 * @param CPrintForm $form
	 * @param CModel $object
	 */
    function __construct($form, $object) {
        $this->form = $form;
        $this->object = $object;
    	
        $file = PRINT_TEMPLATES_DIR.$form->template_file;
        $this->file = $file;
        $path = dirname($file);
        $this->_tempFileName = $path.DIRECTORY_SEPARATOR.time().'.html';
        
		// Copy the source File to the temp File
        copy($file, $this->_tempFileName); 
    }
    
    /**
     * Сохранить шаблон печатной формы
     *
     * @param String $filename
     * @return String
     */
    public function save($filename) {
		rename($this->_tempFileName, $filename);
    }
    
    /**
     * Удалить временный файл печатной формы
     */
    public function deleteTempFile() {
		if(file_exists($this->_tempFileName)) {
			unlink($this->_tempFileName);
		}
    }
    
    /**
     * Получить поля из шаблона.
     * Поля будем получать, используя механизм работы с ODT-файлами.
     * Для этого необходимо, чтобы был указан файл шаблона-основы, из которого сделан HTML-шаблон
     *
     * @return IPrintClassField[]
     */
    public function getFields() {
		$form = $this->form;
		$object = $this->object;
		$file = $this->file;
    	
		$fields = array();
		if ($form->form_odt != 0) {
			$formOdt = CPrintManager::getForm($form->form_odt);
			if ($formOdt->form_format == "odt") {
				$writer = new COdtPrintTemplateWriter($formOdt, $object);
				$odtTemplate = $writer->loadTemplate();
				$fieldsFromTemplate = $odtTemplate->getFields();
				foreach ($fieldsFromTemplate as $templateField) {
					$fields[] = new CHtmlPrintTemplateField($templateField->getName());
				}
				$odtTemplate->deleteTempFile();
			} else {
				throw new Exception("Файл шаблона-основы для HTML должен быть формата ODT!");
			}
		} else {
			/**
			 * Отображаем заранее подготовленный шаблон Smarty
			 */
			$this->setData("plan", $object);
			$this->renderView($file);
			$this->deleteTempFile();
			exit;
		}
		return $fields;
    }
    
}