<?php
/**
 * Компаратор для сортировки образовательных технологий нагрузки раздела категории рабочей программы
 *
 */
class CWorkPlanContentSectionLoadTechnologyComparator {
    public static function compare(CWorkPlanContentSectionLoadTechnology $first, CWorkPlanContentSectionLoadTechnology $second) {
        return strcmp($first->ordering, $second->ordering);
    }
}