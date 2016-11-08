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
		/**
		 * Получаем описатели из базы данных
		 */
		foreach (CPrintManager::getListFieldsByFormset($form->formset_id) as $field) {
			$fields[] = new CHtmlPrintTemplateField($field->alias);
		}
		/**
		 * Получаем описатели-классы
		 */
		$start = get_class($object);
		$end = ".class";
		$text = file_get_contents($file);
		preg_match_all("/$start(.*?)$end/", $text, $result);
		foreach ($result[0] as $field) {
			$fields[] = new CHtmlPrintTemplateField($field);
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