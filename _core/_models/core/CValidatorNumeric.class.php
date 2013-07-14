<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 20:43
 * To change this template use File | Settings | File Templates.
 */

class CValidatorNumeric implements IValidator {
    public function run($value) {
        $res = true;
        if (!is_numeric($value)) {
            $res = false;
        }
        return $res;
    }

    public function getError(){
        return ERROR_FIELD_NUMERIC;
    }

}