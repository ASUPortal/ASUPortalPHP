<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 19:12
 * To change this template use File | Settings | File Templates.
 */

interface IValidator {
    public function run($value);
    public function getError();
}