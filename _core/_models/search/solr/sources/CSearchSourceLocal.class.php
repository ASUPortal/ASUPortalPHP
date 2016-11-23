<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.04.16
 * Time: 20:24
 */

class CSearchSourceLocal extends CComponent implements ISearchSource {
    public $path;
    public $id;
    public $suffix;

    private function scanDirectory($path) {
        return CFileUtils::getListFiles($path);
    }

    /**
     * Получение файлов для индексирования
     *
     * @param CSetting $coreId
     */
    public function getFilesToIndex(CSetting $coreId) {
        /**
         * Получаем настройки коллекции Solr
         */
        $suffix = CSettingsManager::getSettingSolr($coreId, $this->suffix)->getValue();
        $path = CSettingsManager::getSettingSolr($coreId, $this->path)->getValue();
    	
        $files = $this->scanDirectory($path);
        $filelist = array();
        $suffixes = explode(";", $suffix);
        foreach ($files as $file) {
        	$extension = end(explode(".", $file));
        	if (in_array($extension, $suffixes)) {
        		$filelist[] = $file;
        	}
        }
        return new CSearchSourceLocalIterator($filelist, $this);
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @return CSearchFile
     */
    public function getFile(CSearchFile $fileDescriptor) {
        return $fileDescriptor;
    }

}