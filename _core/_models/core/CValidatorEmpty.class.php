<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 19:12
 * To change this template use File | Settings | File Templates.
 */

class CValidatorEmpty implements IValidator {
    public function run($value) {
        $result = true;
        if (is_string($value)) {
            if ($value == "") {
                $result = false;
            }
        }
        return $result;
    }
}