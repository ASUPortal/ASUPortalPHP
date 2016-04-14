<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.04.16
 * Time: 20:24
 */

interface ISearchSource {
    public function getFilesToIndex();
    public function getId();
    public function getFile(CSearchFile $fileDescriptor);
}