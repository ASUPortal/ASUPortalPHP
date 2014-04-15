<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.04.14
 * Time: 14:47
 */

class CDocumentFile extends CDocumentFolder{
    protected $_table = TABLE_DOCUMENTS;
    private $foldersLookup = array(
        "gost1" => "dolg_instr",
        "gost2" => "edu_stand",
        "gost3" => "att_spec",
        "gost4" => "uch_plan",
        "gost5" => "diplom",
        "gost6" => "instr",
        "gost7" => "moodle",
        "gost8" => "practice",
        "gost9" => "umk",
        "gost"  => ""
    );
    public $nameFolder = "gost";

    public function fieldsProperty() {
        return array(
            'nameFile' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS.'library'.CORE_DS.'gost'.CORE_DS
            )
        );
    }

    public function isFolder() {
        return false;
    }

    /**
     * Проверяем, есть ли файл на самом деле
     *
     * @return bool
     */
    public function isFileExists() {
        if (file_exists(CORE_CWD.CORE_DS.'library'.CORE_DS.'gost'.CORE_DS.$this->nameFile)) {
            return true;
        }
        return false;
    }

    /**
     * Ссылка на скачивание файла
     *
     * @return string
     */
    public function getFileLink() {
        if ($this->isFileExists()) {
            if (strpos($this->nameFolder, "gost") !== false) {
                return WEB_ROOT.'/library/gost/'.$this->foldersLookup[$this->nameFolder].'/'.$this->nameFile;
            }
            return WEB_ROOT.'/library/gost/'.$this->nameFile;
        }
        return "";
    }
    public function getIconLink() {
        if ($this->isFileExists()) {
            $filetype = CUtils::getMimetype(CORE_CWD.CORE_DS.'library'.CORE_DS.'gost'.CORE_DS.$this->nameFile);
            if (file_exists(CORE_CWD.CORE_DS."images".CORE_DS.ICON_THEME.CORE_DS."64x64".CORE_DS."mimetypes".CORE_DS.$filetype.".png")) {
                return WEB_ROOT."images/".ICON_THEME."/64x64/mimetypes/".$filetype.".png";
            } else {
                return WEB_ROOT."images/".ICON_THEME."/64x64/mimetypes/text-x-install.png";
            }
        }
        return "";
    }
} 