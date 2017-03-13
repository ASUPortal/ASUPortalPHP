<?php
/**
 * Сравнение полей модели CPerson
 *
 */
class CPersonComparator implements CComparator {
	private $_field;
	
	/**
	 * @param $field
	 */
	function __construct($field) {
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
        $field = $this->_field;
        return strcmp($first->person->$field, $second->person->$field);
    }
}