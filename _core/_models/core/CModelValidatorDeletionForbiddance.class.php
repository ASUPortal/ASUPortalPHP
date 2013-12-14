<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.12.13
 * Time: 20:54
 * To change this template use File | Settings | File Templates.
 */

class CModelValidatorDeletionForbiddance implements IModelValidator{
    public function getError(){
        return "Удаление записей запрещено";
    }

    public function onCreate(CModel $model){
        /**
         * За создание этот валидатор не отвечает, поэтому всегда согласен
         */
        return true;
    }

    public function onRead(CModel $model){
        /**
         * За создание этот валидатор не отвечает, поэтому всегда согласен
         */
        return true;
    }

    public function onUpdate(CModel $model){
        /**
         * За создание этот валидатор не отвечает, поэтому всегда согласен
         */
        return true;
    }

    public function onDelete(CModel $model){
        return false;
    }

}