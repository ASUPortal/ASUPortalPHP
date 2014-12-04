<?php
/**
 * Created by PhpStorm.
 * User: ABarmin
 * Date: 04.12.2014
 * Time: 10:58
 */

class CImportMarksFromCSVForm extends CFormModel{
    private $_fileHandle = null;

    private function getFileHandle() {
        if (is_null($this->_fileHandle)) {
            $props = $this->fieldsProperty();
            $filepath = $props['file']['upload_dir'].$this->file;
            $this->_fileHandle = fopen($filepath, "r");
        }
        return $this->_fileHandle;
    }

    public function attributeLabels(){
        return array(
            "file" => "Файл для импорта"
        );
    }

    /**
     * Получить данные для импорта
     *
     * @return array
     */
    public function getData() {
        $result = array();
        while (($row = fgetcsv($this->getFileHandle(), 10000, ';')) !== false) {
            $result[] = $row;
        }
        return $result;
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "file",
                "person"
            )
        );
    }

    public function fieldsProperty(){
        return array(
            'file' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS."tmp".CORE_DS
            )
        );
    }

}