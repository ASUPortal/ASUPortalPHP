<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 20:42
 * To change this template use File | Settings | File Templates.
 */

class CValidatorSelected implements IValidator{
    public function run($value){
        $res = true;
        if ($value == 0) {
            $res = false;
        }
        return $res;
    }

    public function getError(){
        return ERROR_FIELD_SELECTED;
    }

}