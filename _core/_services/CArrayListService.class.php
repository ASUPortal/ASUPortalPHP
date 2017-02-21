<?php

/**
 * Сервис для работы с массивами
 *
 */
class CArrayListService {
	
    /**
     * Сортировка CArrayList по значению переданного массива $sorted
     * 
     * @param array $sorted
     * @param CArrayList $arrayList
     * @param bool $direction
     * 
     * @return CArrayList
     */
    public static function getSortedByField(array $sorted, CArrayList $arrayList, $direction) {
        if ($direction) {
            asort($sorted);
        } else {
            arsort($sorted);
        }
        $newArrayList = new CArrayList();
        foreach ($sorted as $key=>$value) {
            $newArrayList->add($key, $arrayList->getItem($key));
        }
        return $newArrayList;
    }
    
}