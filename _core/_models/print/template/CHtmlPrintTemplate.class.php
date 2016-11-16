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
 * @property CPrintForm form
 */
class CHtmlPrintTemplate implements IPrintTemplate {
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
     * Получить поля из шаблона.
     * Название описателей-классов должно начинаться с имени класса-объекта
     *
     * @return IPrintClassField[]
     */
    public function getFields() {
		$form = $this->form;
		$object = $this->object;
		$file = $this->file;
		
		$fields = array();
		$formsetFields = new CArrayList();
		/**
		 * Получаем описатели из базы данных
		 */
		$formsetFields = $this->form->formset->fields;
		/**
		 * Получаем описатели-классы
		 */
		$start = get_class($object);
		$end = ".class";
		preg_match_all("/$start(.*?)$end/", file_get_contents($file), $result);
		foreach ($result[0] as $fieldName) {
			$field = CPrintManager::getPrintClassField($fieldName, $object);
			$formsetFields->add($field->alias, $field);
		}
		/**
		 * @var CPrintField $field
		 */
		foreach ($formsetFields as $field) {
			if ($field->getFieldType() == IPrintClassField::FIELD_TEXT) {
				$fields[] = new CHtmlTextPrintTemplateField($field);
			} else if ($field->getFieldType() == IPrintClassField::FIELD_TABLE) {
				$fields[] = new CHtmlTablePrintTemplateField($field);
			} else {
				throw new Exception("Unsupported field type" . $field->type_id);
			}
		}
		return $fields;
    }
    
    /**
     * Заменить изображения в шаблоне на 64-разрядный код
     */
    public function replaceImage64encoded() {
    	/**
    	 * Подключаем PHP Simple HTML DOM Parser
    	 */
    	$tempFile = $this->_tempFileName;
    	require_once(CORE_CWD."/_core/_external/smarty/vendor/simple_html_dom.php");
    	$html = file_get_html($tempFile);
    	/**
    	 * Находим все теги с изображениями на странице
    	*/
    	if (count($html->find('img'))) {
    		foreach ($html->find('img') as $img) {
    			/**
    			 * Заменяем теги с изображениями на 64-разрядный код
    			 */
    			$img->src = CPrintUtils::getBase64encodedImage($img->src);
    		}
    	}
    	/**
    	 * Пишем изменения в файл
    	 */
    	file_put_contents($tempFile, $html->save());
    	$html->clear();
    	unset($html);
    }
    
}