<?php
/**
 * Сравнение для сортировки объектов по связи (при наличии) и полю
 * 
 */
class CDefaultComparator implements CComparator {
	private $_relation;
	private $_field;
	
	/**
	 * @param $model
	 * @param $field
	 */
	function __construct($relation, $field) {
		$this->_relation = $relation;
		$this->_field = $field;
	}
	
    /**
     * Сравниваем объекты. Если они равны, то
     * возвращаем 0, если первый больше, то > 0,
     * если второй больше, то < 0
     * @param $first
     * @param $second
     * @return int
     */
    public function compare($first, $second) {
        $relation = $this->_relation;
        $field = $this->_field;
        if (!is_null($relation)) {
        	return strcmp($first->$relation->$field, $second->$relation->$field);
        } else {
        	return strcmp($first->$field, $second->$field);
        }
    }
}