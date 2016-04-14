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

    private function scanDirectory() {
        return CUtils::getListFiles($this->path);
    }

    public function getFilesToIndex() {
        $files = $this->scanDirectory();
        return new CSearchSourceLocalIterator($files, $this);
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