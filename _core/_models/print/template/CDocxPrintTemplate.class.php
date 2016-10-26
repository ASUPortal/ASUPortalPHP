<?php

/**
 * Шаблон печатной формы на основе DOCX-документа
 *
 * Class CDocxPrintTemplate
 */
class CDocxPrintTemplate implements IPrintTemplate {
	private $form;
	private $object;
	
    /**
     * @param CPrintForm $form
     * @param CModel $object
     */
    function __construct($form, $object) {
		$this->form = $form;
		$this->object = $object;
    	
		$path = dirname($file);
		$this->_tempFileName = $path.DIRECTORY_SEPARATOR.time().'.docx';
		
		// Copy the source File to the temp File
		//copy($file, $this->_tempFileName); 
    }
    
    /**
     * Сохранить шаблон печатной формы
     *
     * @param String $filename
     * @return String
     */
    public function save($filename);
    
    /**
     * Получить поля из шаблона
     *
     * @return IPrintClassField[]
    */
    public function getFields();
    
    /**
     * Удалить временный файл печатной формы
     */
    public function deleteTempFile() {
		if(file_exists($this->_tempFileName)) {
			unlink($this->_tempFileName);
		}
    }
    
}