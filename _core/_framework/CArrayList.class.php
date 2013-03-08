<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 04.05.12
 * Time: 22:22
 * To change this template use File | Settings | File Templates.
 *
 * Прокачанный массив с дополнительными возможностями.
 * Во всем приложении будем использовать его как базовую структуру
 * хранения даных.
 */
class CArrayList {
    private $_items;

    public function __construct() {
        $this->_items = array();
    }

    public function add($key, $val) {
        $this->_items[$key] = $val;
    }
    /**
     * @return int
     */
    public function getCount() {
        return count($this->_items);
    }
    /**
     * @param $key
     * @return bool
     */
    public function hasElement($key) {
        if (array_key_exists($key, $this->_items)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * @return array
     */
    public function getItems() {
        return $this->_items;
    }

    public function getItem($key) {
        if ($this->hasElement($key)) {
            return $this->_items[$key];
        };
        return null;
    }
    /**
     * Лист, отсортированный по ключу
     * true - по возрастанию,
     * false - по убыванию
     *
     * @param $direction
     * @return CArrayList
     */
    public function getSortedByKey($direction) {
        $keys = array_keys($this->_items);
        if ($direction) {
            asort($keys);
        } else {
            arsort($keys);
        }
        $newArray = new CArrayList();
        foreach ($keys as $key) {
            $newArray->add($key, $this->_items[$key]);
        }
        return $newArray;
    }
    public function removeItem($key) {
        if ($this->hasElement($key)) {
            unset($this->_items[$key]);
        }
    }
    /**
     * Лист со случайной сортировкой
     *
     * @return CArrayList
     */
    public function getShuffled() {
        $keys = array_keys($this->_items);
        shuffle($keys);
        $newArray = new CArrayList();
        foreach ($keys as $key) {
            $newArray->add($key, $this->_items[$key]);
        }
        return $newArray;
    }
    /**
     * Первый элемент из списка
     *
     * @return mixed
     */
    public function getFirstItem() {
        if ($this->getCount() == 0) {
            return null;
        }
        return $this->_items[$this->getFirstItemKey()];
    }

    /**
     * Ключ первого элемента списка
     *
     * @return string
     */
    public function getFirstItemKey() {
        if ($this->getCount() == 0) {
            return "";
        }
        $keys = array_keys($this->_items);
        return $keys[0];
    }

    /**
     * Последний элемент из списка
     * @return null
     */
    public function getLastItem() {
        if ($this->getCount() == 0) {
            return null;
        }
        $keys = array_keys($this->_items);
        foreach ($keys as $key) {
            $last = $key;
        }
        return $this->_items[$last];
    }

    /**
     * Копия текущего объекта как новый объект
     *
     * @return CArrayList
     */
    public function getCopy() {
        $res = new CArrayList();
        foreach ($this->_items as $key=>$value) {
            $res->add($key, $value);
        }
        return $res;
    }
}
