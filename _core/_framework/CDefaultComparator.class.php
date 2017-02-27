<?php
class CDefaultComparator implements CComparator {
	
	/**
	 * @param $field
	 */
	function __construct($field) {
		$this->field = $field;
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
        $field = $this->field;
        return strcmp($first->$field, $second->$field);
    }
}