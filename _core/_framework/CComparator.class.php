<?php
interface CComparator {
    /**
     * Сравниваем объекты. Если они равны, то
     * возвращаем 0, если первый больше, то >0,
     * если второй больше, то <0
     * @param $obj1
     * @param $obj2
     * @return int
     **/
    function compare($obj1, $obj2);
}