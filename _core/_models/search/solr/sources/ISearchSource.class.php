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
	/**
	 * Получение файлов для индексирования
	 */
    public function getFilesToIndex();
    /**
     * Идентификатор источника данных
     */
    public function getId();
    /**
     * Получение файла по идентификатору файла в индексе Solr
     * 
     * @param CSearchFile $fileDescriptor
     */
    public function getFile(CSearchFile $fileDescriptor);
}