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
     * @param CSearchSettings $coreId
     */
    function __construct($filesList, $source, CSearchSettings $coreId) {
    	/**
    	 * Получаем настройки коллекции Solr
    	 */
    	foreach ($coreId->getSearchSettingsList() as $setting) {
    		if ($setting->getAlias() == "ftp_server") {
    			$this->server = $setting->getValue();
    		}
    		if ($setting->getAlias() == "ftp_server_user") {
    			$this->login = $setting->getValue();
    		}
    		if ($setting->getAlias() == "ftp_server_password") {
    			$this->password = $setting->getValue();
    		}
    	}
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