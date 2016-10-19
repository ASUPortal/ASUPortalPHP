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
 * Массив с вынесенными отдельно параметрами, методами, объектами
 */
class CArrayList implements Iterator{
    private $_items;
    protected $_position = 0;

    public function __construct($array = array()) {
        $this->_items = $array;
        $this->_position = 0;
    }

    public function add($key, $val) {
        $this->_items[$key] = $val;
    }

    public function addAll($array = array()) {
        foreach ($array as $key=>$value) {
            $this->add($key, $value);
        }
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
    /**
     * Проверка массива на пустоту
     * 
     * @return bool
     */
    public function isEmpty() {
        return empty($this->getItems());
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

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current() {
        $values = array_values($this->_items);
        return $values[$this->_position];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next() {
        ++$this->_position;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key() {
        $keys = array_keys($this->_items);
        if (array_key_exists($this->_position, $keys)) {
            return $keys[$this->_position];
        }
        return null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid() {
        return $this->hasElement($this->key());
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        $this->_position = 0;
    }


}
