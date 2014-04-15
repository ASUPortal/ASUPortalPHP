<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 04.05.12
 * Time: 22:18
 * To change this template use File | Settings | File Templates.
 *
 * ORM-связка объекта с таблицей базы данных
 */
class CActiveRecord {
    // параметры соединения с БД - откуда выбирать
    // что ключ и значение ключа
    private $_table = null;
    protected $_id = null;
    protected static $_pk = 'id';
    private $_items = null;
    private $_needSave = false;

    /**
     * Конструктор. Принимает mysql array для текущей записи
     * Напрямую не вызывать, через статические методы
     *
     * @param $arr
     * @return CActiveRecord
     */
    public function __construct($arr) {
        $this->_id = $arr[self::getPk()];
        $this->_items = $arr;
        return $this;
    }
    /**
     * Таблица, к которой AR относится
     *
     * @param $table
     */
    public function setTable($table) {
        $this->_table = $table;
    }
    /**
     * Значение какого-либо поля
     *
     * @param $key
     * @return mixed
     */
    public function getItemValue($key) {
        return $this->_items[$key];
    }
    /**
     * Установка значения какого-либо поля
     *
     * @param $key
     * @param $value
     */
    public function setItemValue($key, $value) {
        $this->_items[$key] = $value;
        $this->_needSave = true;
    }
    /**
     * id текущего объекта.
     * желательно, чтобы ключ не был составным
     *
     * @return int
     */
    public function getId() {
        if (is_null($this->_id)) {
            $this->_id = $this->_items[$this->getPk()];
        }
        return $this->_id;
    }
    /**
     * Имя поля первичного ключа
     *
     * @static
     * @return string
     */
    public static function getPk() {
        return static::$_pk;
    }

    /**
     * Установим имя поля первичного ключа
     *
     * @param $key
     */
    public static function setPk($key) {
        self::$_pk = $key;
    }
    /**
     * Имя класса текущего объекта
     *
     * @static
     * @return string
     */
    public static function getClassName() {
        return __CLASS__;
    }
    /**
     * Имя таблицы, к которой сущность привязана. Если имя
     * таблицы не указано, то оно определяется по имени класса
     *
     * @return string
     */
    public function getTable() {
        if (is_null($this->_table)) {
            $this->_table = static::getClassName();
        }
        return $this->_table;
    }
    /**
     * Обновление существующей записи
     */
    public function update() {
        $q = new CQuery();
        $q->update($this->_table, $this->_items)
        ->condition($this->getPk()."=".$this->getId())
        ->execute();
    }
    public function insert() {
        $q = new CQuery();
        $q->insert($this->_table, $this->_items)
        ->execute();
    }
    /**
     * Список полей записи
     *
     * @return array
     */
    public function getItems() {
        return $this->_items;
    }
    /**
     * Заменяет список полей записи
     *
     * @param $arr array
     */
    public function setItems($arr) {
        $this->_items = $arr;
        if (array_key_exists("id", $arr)) {
            $this->_id = $arr['id'];
        }
    }
    /**
     * Проверяет, есть ли у модели указанное поле
     *
     * @param $itemName
     * @return bool
     */
    public function hasItem($itemName) {
        if (array_key_exists($itemName, $this->getItems())) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Удаление записи из БД
     */
    public function remove() {
        $q = new CQuery();
        $q->remove($this->_table)
        ->condition($this->getPk()."=".$this->getId())
        ->execute();
    }

    /**
     * Удалить значение поля из записи
     *
     * @param $name
     */
    public function unsetItem($name) {
        if (array_key_exists($name, $this->getItems())) {
            unset($this->_items[$name]);
        }
    }
}
