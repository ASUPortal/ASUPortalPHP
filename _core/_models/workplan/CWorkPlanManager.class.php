<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.03.15
 * Time: 21:51
 */

class CWorkPlanManager {
    /**
     * @param $id
     * @return CWorkPlan
     */
    public static function getWorkplan($id) {
        $plan = null;
        $ar = CActiveRecordProvider::getById(TABLE_WORK_PLANS, $id);
        if (!is_null($ar)) {
            $plan = new CWorkPlan($ar);
        }
        return $plan;
    }

    /**
     * @param $id
     * @return CWorkPlanCompetention
     */
    public static function getWorkplanCompetention($id) {
        $competention = null;
        $ar = CActiveRecordProvider::getById(TABLE_WORK_PLAN_COMPETENTIONS, $id);
        if (!is_null($ar)) {
            $competention = new CWorkPlanCompetention($ar);
        }
        return $competention;
    }
    
    /**
     * Формируемые компетенции рабочей программы по id программы и id компетенции
     *
     * @param CWorkPlan $plan
     * @param CTerm $competention
     * @return CArrayList
     */
    public static function getWorkplanCompetentionFormed(CWorkPlan $plan, CTerm $competention) {
    	$competentions = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_COMPETENTIONS, "plan_id = ".$plan->getId()." AND competention_id = ".$competention->id." AND type = 0")->getItems() as $item) {
    		$competention = new CWorkPlanCompetention($item);
    		$competentions->add($competention->getId(), $competention);
    	}
    	return $competentions;
    }
    /**
     * Смена статуса рабочей программы, значение берётся из указанного словаря $taxonomy
     * 
     * @param $id - id рабочей программы
     * @param $taxonomy - название справочника
     * @param $status - поле в базе
     * @param $statusItem - отношение CWorkPlan
     * @return json_encode
     */
    public static function updateStatusWorkplan($id, $taxonomy, $status, $statusItem) {
    	$plan = CWorkPlanManager::getWorkplan($id);
    	$result = array(
    		"title" => "–",
    		"color" => "white"
    	);
    	$terms = array();
    	foreach (CTaxonomyManager::getTaxonomy($taxonomy)->getTerms() as $term) {
    		$terms[$term->getId()] = $term->getId();
    	}
    	// сортируем массив по ключам
    	ksort($terms);
    	$termsList = array();
    	foreach ($terms as $term) {
    		$termsList[] = $term;
    	}
    	$current = array_search($plan->$status, $termsList);
    	// меняем на следующий статус
    	if ($current === false) {
    		$plan->$status = $termsList[0];
    		$result["title"] = $plan->$statusItem->getValue();
    		$result["color"] = $plan->$statusItem->getAlias();
    	} elseif ($current == (count($termsList) - 1)) {
    		$plan->$status = 0;
    	} else {
    		$plan->$status = $termsList[$current + 1];
    		$result["title"] = $plan->$statusItem->getValue();
    		$result["color"] = $plan->$statusItem->getAlias();
    	}
    	$plan->save();
    	return $result;
    }
}