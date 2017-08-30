<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.04.16
 * Time: 20:25
 */

class CSearchSourceSamba extends CComponent implements ISearchSource {
    public $id;

    /**
     * Получение файлов для индексирования
     *
     * @param CSetting $coreId
     */
    public function getFilesToIndex(CSetting $coreId) {
        return array();
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    public function getFile(CSearchFile $fileDescriptor) {
        // TODO: Implement getFile() method.
    }


}