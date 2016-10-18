<?php

class CSearchSourceFTPIterator implements Iterator {
    private $server;
    private $login;
    private $password;
    private $filesList;
    private $source;
    private $index;

    function __construct($filesList, $source) {
        $this->server = CSettingsManager::getSettingValue("ftp_server");
        $this->login = CSettingsManager::getSettingValue("ftp_server_user");
        $this->password = CSettingsManager::getSettingValue("ftp_server_password");
        $this->filesList = $filesList;
        $this->source = $source;
    }


    public function current() {
        if (array_key_exists($this->index, $this->filesList)) {
            $file = $this->filesList[$this->index];
            
            // выбираем из названия файла путь до локальной папки и до папки ftp сервера
            $localFile = CUtils::strLeft($file, "||");
            $serverFile = CUtils::strRightBack($file, "||");
            
            $fileObject = new CSearchFile();
            $fileObject->setFileSource($localFile);
            $fileObject->setRealFilePath("ftp://".$this->login.":".$this->password."@"."$this->server"."/".$serverFile);
            $fileObject->setFileLocation("ftp://".$this->server."/".$serverFile);
            $fileObject->setSourceId($this->source->getId());

            return $fileObject;
        }
        return null;
    }

    public function next() {
        $this->index++;
    }

    public function key() {
        return $this->index;
    }

    public function valid() {
        return array_key_exists($this->index, $this->filesList);
    }

    public function rewind() {
        $this->index = 0;
    }

}