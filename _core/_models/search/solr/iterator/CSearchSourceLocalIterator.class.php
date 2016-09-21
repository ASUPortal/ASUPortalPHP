<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.04.16
 * Time: 20:54
 */

class CSearchSourceLocalIterator implements Iterator {
    private $filesList;
    private $source;
    private $index;

    function __construct($filesList, $source) {
        $this->filesList = $filesList;
        $this->source = $source;
    }


    public function current() {
    	$pathRoot = CUtils::getDocumentRoot();
        if (array_key_exists($this->index, $this->filesList)) {
            $file = $this->filesList[$this->index];
            
            // убираем из пути к файлу корневую директорию сервера
            $filePath = str_replace(mb_strtolower($pathRoot), "", mb_strtolower($file));

            $fileObject = new CSearchFile();
            $fileObject->setFileSource($file);
            $fileObject->setRealFilePath("http://".DB_HOST.CORE_DS.$filePath);
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