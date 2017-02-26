<?php
/**
 * Компаратор для сортировки тем нагрузки раздела категории рабочей программы
 *
 */
class CWorkPlanContentSectionLoadTopicComparator {
    public static function compare(CWorkPlanContentSectionLoadTopic $first, CWorkPlanContentSectionLoadTopic $second) {
        return strcmp($first->ordering, $second->ordering);
    }
}