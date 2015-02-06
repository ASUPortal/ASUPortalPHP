<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 06.02.15
 * Time: 16:47
 */

abstract class CAbstractPrintClassField implements IPrintClassField{
    public function getFilePath()
    {
        return __FILE__;
    }

    abstract public function getFieldName();

    abstract public function getFieldDescription();

    abstract public function getParentClassField();

    abstract public function getFieldType();

    abstract public function execute($contextObject);
}