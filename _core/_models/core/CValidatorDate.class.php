<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 20:45
 * To change this template use File | Settings | File Templates.
 */

class CValidatorDate implements IValidator{
    public function run($value){
        $res = true;
        if (strtotime($value) === false) {
            $res = false;
        } else {
            $dateArray = explode(".", $value);
            if (!checkdate($dateArray[1], $dateArray[0], $dateArray[2])) {
                $res = false;
            }
        }
        return $res;
    }

    public function getError(){
        return ERROR_FIELD_NOT_A_DATE;
    }

}