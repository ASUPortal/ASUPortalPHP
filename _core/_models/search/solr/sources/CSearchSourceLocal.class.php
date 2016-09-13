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

    private function scanDirectory() {
        return CUtils::getListFiles(CSettingsManager::getSettingValue($this->path));
    }

    public function getFilesToIndex() {
        $files = $this->scanDirectory();
        $filelist = array();
        $suffixes = explode(";", CSettingsManager::getSettingValue($this->suffix));
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

    public function getFile(CSearchFile $fileDescriptor) {
        return $fileDescriptor->getRealFilePath();
    }


}