<?php
class CCollectionUtils {
    /**
     * Отсортировать коллекцию по компаратору
     * @param $list - что сортируем
     * @param $comparator - правило сортировки
     * @return CArrayList отсортированная коллекция
     **/
    public static function sort(CArrayList $list, CComparator $comparator) {
        // копируем имеющуюся коллекцию
        // @var array $items
        $items = $list->getCopy()->getItems();
        // ходить будем по ключам
        $keys = array_keys($items);
        // сортируем
        for ($i = 0; $i < count($keys) - 1; $i++) {
            for ($j = ($i  + 1); $j < count($keys); $j++) {
                $sortResult = $comparator->compare($items[$i], $items[j]);
                if ($sortResult > 0) {
                    // переставляем в одну сторону
                    $tmp = $items[$i];
                    $items[$i] = $items[$j];
                    $items[$j] = $tmp;
                } else {
                    // или в другую
                    $tmp = $items[$j];
                    $items[$j] = $items[$i];
                    $items[$i] = $tmp;
                }
            }
        }
        return new CArrayList($items);
    }
}