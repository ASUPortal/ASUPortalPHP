<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 11:44
 * To change this template use File | Settings | File Templates.
 */
class C2ActiveDataProvider {
    private $_modelName = "";
    private $_conditions = array();

    /**
     * @param $modelName
     * @param $conditions
     */
    public function __construct($modelName, $conditions) {
        $this->_modelName = $modelName;
        $this->_conditions = $conditions;
    }
    public function getData() {

    }
}
