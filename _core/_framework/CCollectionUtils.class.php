<?php
class CCollectionUtils {
    /**
     * Отсортировать коллекцию по компаратору
     * @param $list - что сортируем
     * @param $comparator - правило сортировки
     * @param $field - поле, по которому сортируем
     * @return CArrayList отсортированная коллекция
     **/
    public static function sort(CArrayList $list, CComparator $comparator, $field) {
        /**
         * Копируем имеющуюся коллекцию
         * @var array $items
         */
        $items = $list->getCopy()->getItems();
        // ходить будем по ключам
        $keys = array_keys($items);
        // сортируем
        for ($i = 0; $i < count($keys) - 1; $i++) {
            for ($j = ($i + 1); $j < count($keys); $j++) {
                $sortResult = $comparator->compare($items[$keys[$i]], $items[$keys[$j]], $field);
                // если текущий элемент больше следующего
                if ($sortResult > 0) {
                	// меняем местами элементы
                	$tmp = $items[$keys[$j]];
                	$items[$keys[$j]] = $items[$keys[$i]];
                	$items[$keys[$i]] = $tmp;
                }
            }
        }
        return new CArrayList($items);
    }
}