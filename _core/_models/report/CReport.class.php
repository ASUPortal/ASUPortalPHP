<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 19.04.14
 * Time: 15:49
 * To change this template use File | Settings | File Templates.
 */

class CReport extends CActiveModel{
    protected $_table = TABLE_REPORTS;
    private $_object = null;

    public $active = 0;

    /**
     * @return CReportObjectAbstract
     */
    public function getReportObject() {
        if (is_null($this->_object)) {
            $class = $this->class;
            $this->_object = new $class();
        }
        return $this->_object;
    }
}