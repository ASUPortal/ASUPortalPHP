<?php

class CSearchSourceFTPIterator implements Iterator {
    private $server;
    private $login;
    private $password;
    private $filesList;
    private $source;
    private $index;

    /**
     * @param array $filesList
     * @param ISearchSource $source
     * @param CSetting $coreId
     */
    function __construct($filesList, $source, CSetting $coreId) {
        /**
         * Получаем настройки коллекции Solr
         */
        $this->server = CSettingsManager::getSettingSolr($coreId, $this->server)->getValue();
        $this->login = CSettingsManager::getSettingSolr($coreId, $this->login)->getValue();
        $this->password = CSettingsManager::getSettingSolr($coreId, $this->password)->getValue();
    	
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