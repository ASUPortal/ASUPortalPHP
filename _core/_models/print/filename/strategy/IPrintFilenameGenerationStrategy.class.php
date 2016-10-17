<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 21:03
 */

interface IPrintFilenameGenerationStrategy {
    /**
     * Сгенерировать имя файла
     *
     * @return String
     */
    public function getFilename();
}