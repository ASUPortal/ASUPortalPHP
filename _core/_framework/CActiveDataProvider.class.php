<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 19:39
 * To change this template use File | Settings | File Templates.
 */
class CActiveDataProvider {
    private $_condition = array();
    private $_modelName = "";
    public function __construct($modelName, $condition = array()) {
        $this->_modelName = $modelName;
        $this->_condition = $condition;
    }
    public function getData() {

    }
}
