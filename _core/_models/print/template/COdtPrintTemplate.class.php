<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:39
 */

/**
 * Шаблон печатной формы на основе ODT-документа
 *
 * Class COdtPrintTemplate
 * @property CPrintForm form
 */
class COdtPrintTemplate implements IPrintTemplate {
	private $form;
	private $object;
	private $_xmlDocument = null;
	private $_xmlStyle = null;
	public $_documentXML = null;
	public $_styleXML = null;
	public $_tempFileName = null;

    /**
     * @param CPrintForm $form
     * @param CModel $object
     */
    function __construct($form, $object) {
		$this->form = $form;
		$this->object = $object;
    	
		$file = PRINT_TEMPLATES_DIR.$form->template_file;
		$path = dirname($file);
		$this->_tempFileName = $path.DIRECTORY_SEPARATOR.time().'.odt';
        
		// Copy the source File to the temp File
		copy($file, $this->_tempFileName); 
        
		$this->_objZip = new ZipArchive();
		$this->_objZip->open($this->_tempFileName);
        
		$this->_documentXML = $this->_objZip->getFromName('content.xml');
		$this->_styleXML = $this->_objZip->getFromName('styles.xml');
    }

    /**
     * Получить поля из шаблона
     *
     * @return IPrintClassField[]
     */
    public function getFields() {
        $fields = array();
        foreach ($this->getDocumentFields() as $name => $descriptors) {
            /**
             * Обрабатываем описатели из базы данных
             * @var CPrintField $field
             */
            if (!is_null($this->form->formset->getFieldByName($name))) {
                $field = $this->form->formset->getFieldByName($name);
            }
            /**
             * Получаем описатели-классы
             * @var IPrintClassField $field
             */
            if (!is_null(CPrintManager::getPrintClassField($name, $this->object))) {
                $field = CPrintManager::getPrintClassField($name, $this->object);
            }
            $fields[] = new COdtPrintTemplateField($field, $this->form, $descriptors, false);
        }
        foreach ($this->getStyleFields() as $name => $descriptors) {
            /**
             * Обрабатываем описатели из базы данных
             * @var CPrintField $field
             */
            if (!is_null($this->form->formset->getFieldByName($name))) {
                $field = $this->form->formset->getFieldByName($name);
            }
            /**
             * Получаем описатели-классы
             * @var IPrintClassField $field
             */
            if (!is_null(CPrintManager::getPrintClassField($name, $this->object))) {
                $field = CPrintManager::getPrintClassField($name, $this->object);
            }
            $fields[] = new COdtPrintTemplateField($field, $this->form, $descriptors, true);
        }
        return $fields;
    }
    
    /**
     * Все поля, которые есть в документе
     *
     * @return array
     */
    private function getDocumentFields() {
        $fields = array();
        $nodes = $this->getXMLDocument()->getElementsByTagNameNS("urn:oasis:names:tc:opendocument:xmlns:text:1.0", "user-field-get");
        foreach ($nodes as $node) {
            /**
             * А ведь в документе может быть несколько одинаковых
             * описателей. Складываем все в массив
             */
            $descriptors = array();
            if (array_key_exists($node->textContent, $fields)) {
                $descriptors = $fields[$node->textContent];
            }
            $descriptors[] = $node;
            $fields[$node->textContent] = $descriptors;
        }
        return $fields;
    }
    
    /**
     * XML в виде объекта DOMDocument
     *
     * @return DOMDocument|null
     */
    private function getXMLDocument() {
		if (is_null($this->_xmlDocument)) {
			$doc = new DOMDocument();
			$doc->loadXML($this->getDocXML());
			$this->_xmlDocument = $doc;
		}
		return $this->_xmlDocument;
    }
    
    /**
     * Текст xml-документа, который лежит в основе
     *
     * @return mixed|null
     */
    private function getDocXML() {
		return $this->_documentXML;
    }
    
    /**
     * Содержимое файла в виде строки
     *
     * @param $xmlString
     */
    public function setDocXML($xmlString) {
		$this->_documentXML = $xmlString;
    }
    
    /**
     * Все поля из файла стилей
     *
     * @return array
     */
    private function getStyleFields() {
		$fields = array();
		$nodes = $this->getXMLStyle()->getElementsByTagNameNS("urn:oasis:names:tc:opendocument:xmlns:text:1.0", "user-field-get");
		foreach ($nodes as $node) {
			/**
			 * А ведь в документе может быть несколько одинаковых
			 * описателей. Складываем все в массив
			 */
			$descriptors = array();
			if (array_key_exists($node->textContent, $fields)) {
				$descriptors = $fields[$node->textContent];
			}
			$descriptors[] = $node;
			$fields[$node->textContent] = $descriptors;
		}
		return $fields;
    }

    /**
     * XML стиля в виде объекта DOMDocument
     *
     * @return DOMDocument|null
     */
    private function getXMLStyle() {
		if (is_null($this->_xmlStyle)) {
			$doc = new DOMDocument();
			$doc->loadXML($this->getStyleXML());
			$this->_xmlStyle = $doc;
		}
		return $this->_xmlStyle;
    }
    
    /**
     * Текст xml-стиля
     *
     * @return mixed|null
     */
    private function getStyleXML() {
		return $this->_styleXML;
    }
    
    /**
     * Содержимое стилей в виде строки
     *
     * @param unknown $xmlString
     */
    public function setStyleXML($xmlString) {
		$this->_styleXML = $xmlString;
    }
}