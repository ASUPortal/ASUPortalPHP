<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 24.09.12
 * Time: 22:40
 * To change this template use File | Settings | File Templates.
 */
class CFormModel extends CModel {
    private $_items = null;
    /**
     * @return CArrayList
     */
    protected function getItems() {
        if (is_null($this->_items)) {
            $this->_items = new CArrayList();
        }
        return $this->_items;
    }
    public function __get($name) {
        return $this->getItems()->getItem($name);
    }
    public function __set($name, $value) {
        $this->getItems()->add($name, $value);
    }
}
