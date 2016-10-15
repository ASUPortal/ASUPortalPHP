<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.04.16
 * Time: 20:42
 */

class CSearchFile {
    private $fileSource; // откуда взять файл
    private $realFilePath; // там, где он на самом деле лежит
    private $fileLocation; // расположение файла
    private $sourceId;
    private $fileId;

    /**
     * @return mixed
     */
    public function getFileId() {
        return $this->sourceId . "||" . md5($this->realFilePath);
    }

    /**
     * @return mixed
     */
    public function getFileSource()
    {
        return $this->fileSource;
    }

    /**
     * @param mixed $fileSource
     */
    public function setFileSource($fileSource)
    {
        $this->fileSource = $fileSource;
    }

    /**
     * @return mixed
     */
    public function getRealFilePath()
    {
        return $this->realFilePath;
    }

    /**
     * @param mixed $realFilePath
     */
    public function setRealFilePath($realFilePath)
    {
        $this->realFilePath = $realFilePath;
    }

    /**
     * @return mixed
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @param mixed $sourceId
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
    } // идентификатор источника данных
    
    /**
     * @return mixed
     */
    public function getFileLocation()
    {
    	return $this->fileLocation;
    }
    
    /**
     * @param mixed $fileLocation
     */
    public function setFileLocation($fileLocation)
    {
    	$this->fileLocation = $fileLocation;
    }


}