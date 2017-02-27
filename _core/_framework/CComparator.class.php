<?php
class CComparator {
    /**
     * Сравниваем объекты. Если они равны, то
     * возвращаем 0, если первый больше, то > 0,
     * если второй больше, то < 0
     * @param $first
     * @param $second
     * @param $field
     * @return int
     **/
    public function compare($first, $second, $field) {
        return strcmp($first->$field, $second->$field);
    }
}