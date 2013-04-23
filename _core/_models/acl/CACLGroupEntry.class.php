<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 04.01.13
 * Time: 16:30        333
 * To change this template use File | Settings | File Templates.
 */
class CACLGroupEntry extends CActiveModel {
    protected $_table = TABLE_USER_IN_GROUPS;
    private $_type = null;
    private $_value = null;
    public function save() {
        if ($this->getType() == 1) {
            $this->getRecord()->setTable(TABLE_USER_IN_GROUPS);
            $this->user_id = $this->getValue();
        } elseif($this->getType() == 2) {
            $this->getRecord()->setTable(TABLE_USER_GROUPS_HIERARCHY);
            $this->child_id = $this->getValue();
        } else {
            return true;
        }
        parent::save();
    }
    private function getValue() {
        return $this->_value;
    }
    public function setValue($value) {
        $this->_value = $value;
    }
    public function setType($type) {
        $this->_type = $type;
    }
    private function getType() {
        return $this->_type;
    }
}
