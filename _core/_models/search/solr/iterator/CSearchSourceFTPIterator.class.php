<?php

class CSearchSourceFTPIterator implements Iterator {
    private $filesList;
    private $source;
    private $index;

    function __construct($filesList, $source) {
        $this->filesList = $filesList;
        $this->source = $source;
    }


    public function current() {
        if (array_key_exists($this->index, $this->filesList)) {
            $file = $this->filesList[$this->index];

            $fileObject = new CSearchFile();
            $fileObject->setFileSource($file);
            $fileObject->setRealFilePath($file);
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