<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.03.13
 * Time: 20:45
 * To change this template use File | Settings | File Templates.
 *
 * Абстракция для представления таблицы базы данных
 */
class CDbTable {
    private $_fields = null;
    public $name;
    public function __construct($name) {
        $this->name = $name;
    }
    public function getFields() {
        if (is_null($this->_fields)) {
            $this->_fields = new CArrayList();
            $query = new CQuery();
            $query->query("DESCRIBE ".$this->name);
            foreach ($query->execute()->getItems() as $field) {
                $this->_fields->add($field["Field"], new CDbTableField($field));
            }
        }
        return $this->_fields;
    }
}
