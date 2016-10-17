<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.04.16
 * Time: 20:24
 * 
 * Интерфейс по работе с файлами различных источников данных для индексации в Solr
 */

interface ISearchSource {
    public function getFilesToIndex(); // получение файлов для индексирования
    public function getId(); // идентификатор источника данных
    public function getFile(CSearchFile $fileDescriptor); // получение файла по идентификатору файла в индексе Solr
}