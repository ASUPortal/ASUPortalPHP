<?php
/**
 * Created by PhpStorm.
 * User: ABarmin
 * Date: 04.12.2014
 * Time: 10:08
 */

interface IImportProvider {
    /**
     * @return CFormModel
     */
    public function getImportModel();
    public function getImportFormName();
    public function import(CFormModel $source);
} 