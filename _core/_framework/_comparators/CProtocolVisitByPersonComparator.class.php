<?php
/**
 * Сортировка посещаемости заседаний кафедры для протоколов по ФИО из модели CPerson
 *
 */
class CProtocolVisitByPersonComparator implements CComparator {
	
    /**
     * Сравниваем объекты. Если они равны, то
     * возвращаем 0, если первый больше, то > 0,
     * если второй больше, то < 0
     * @param $first
     * @param $second
     * @return int
     */
    public function compare($first, $second) {
        return strcmp($first->person->fio, $second->person->fio);
    }
}