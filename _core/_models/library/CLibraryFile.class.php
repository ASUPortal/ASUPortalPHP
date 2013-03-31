<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 17:30
 * To change this template use File | Settings | File Templates.
 */

class CLibraryFile extends CActiveModel{
    protected $_table = TABLE_LIBRARY_FILES;
    protected $_document = null;

    /**
     * Ну вот на кой так было нужно?
     *
     * @return int|mixed|null
     */
    public function getId() {
        return $this->id_file;
    }
    public function relations() {
        return array(
            "document" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_document",
                "storageField" => "nameFolder",
                "managerClass" => "CLibraryManager",
                "managerGetObject" => "getDocumentByFolderId"
            )
        );
    }
    public function getAuthorName() {
        $result = "";
        /**
         * Если включена защита персональных данных и
         * пользователь не авторизован, то не показываем ссылку
         */
        if (CSettingsManager::getSettingValue("hide_personal_data")) {
            if (!CSession::isAuth()) {
                return $result;
            }
        }
        if (!is_null($this->document)) {
            if (!is_null($this->document->person)) {
                $result = $this->document->person->getName();
            }
        }
        return $result;
    }
    public function getAuthorId() {
        $result = "";
        if (!is_null($this->document)) {
            if (!is_null($this->document->person)) {
                $result = $this->document->person->getId();
            }
        }
        return $result;
    }
    /**
     * Значок для файла
     *
     * @return string
     */
    public function getIconImagePath() {
        return CUtils::getFileMimeIcon($this->nameFile);
    }

    /**
     * Ссылка для скачивания. Нужна для учета количества скачиваний
     *
     * @return string
     */
    public function getDownloadLink() {
        return WEB_ROOT."_modules/_library/?action=get&id=".$this->getId();
    }

    /**
     * Реальное положение файла
     *
     * @return string
     */
    public function getFileDownloadLink() {
        return WEB_ROOT."library/".$this->nameFolder."/".$this->nameFile;
    }
}