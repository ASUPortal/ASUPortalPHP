<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 11.12.15
 * Time: 22:46
 */

abstract class IModelValidatorOptional implements  IModelValidator{
    public function onCreate(CModel $model) {
        return true;
    }

    abstract function onRead(CModel $model);

    public function onUpdate(CModel $model) {
        return true;
    }

    public function onDelete(CModel $model) {
        return true;
    }

}