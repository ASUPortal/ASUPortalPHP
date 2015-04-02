<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.03.13
 * Time: 20:45
 * To change this template use File | Settings | File Templates.
 *
 * Абстракция для представления таблицы базы данных
 *
 * @property CArrayList $_fields
 */
class CDbTable {
    private static $_fields = null;
    public $name;
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * @return CArrayList
     */
    public function getFields() {
        if (is_null(self::$_fields)) {
            self::$_fields = new CArrayList();
        }
        if (!self::$_fields->hasElement($this->name)) {
            $fields = new CArrayList();
            $query = new CQuery();
            $query->query("DESCRIBE ".$this->name);
            foreach ($query->execute()->getItems() as $field) {
                $fields->add($field["Field"], new CDbTableField($field));
            }
            self::$_fields->add($this->name, $fields);
        }
        return self::$_fields->getItem($this->name);
    }
}
