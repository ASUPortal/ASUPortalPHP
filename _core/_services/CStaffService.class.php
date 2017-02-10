<?php

/**
 * Сервис по работе с сотрудниками
 *
 */
class CStaffService {
	
    /**
     * ФИО заведующего кафедрой или исполняющего его обязанности с добавлением названия должности, если $withPost = true
     * 
     * @param boolean $withPost
     * @return string
     */
    public static function getHeadOrActingOfDepartment($withPost) {
        $person = "";
        
        $headOfDepartment = CTaxonomyManager::getLegacyTaxonomy(TABLE_POSTS)->getTerm(CPostConstants::HEAD_OF_DEPARTMENT);
        $actingHeadOfDepartment = CTaxonomyManager::getLegacyTaxonomy(TABLE_POSTS)->getTerm(CPostConstants::ACTING_HEAD_OF_DEPARTMENT);
        
        if (!is_null(CStaffManager::getPersonByPostId($actingHeadOfDepartment->getId()))) {
        	if ($withPost) {
        		$person = $actingHeadOfDepartment->name_short." ".CStaffManager::getPersonByPostId($actingHeadOfDepartment->getId())->getNameShort();
        	} else {
        		$person = CStaffManager::getPersonByPostId($actingHeadOfDepartment->getId())->getNameShort();
        	}
        } else {
        	if ($withPost) {
        		$person = $headOfDepartment->name_short." ".CStaffManager::getPersonByPostId($headOfDepartment->getId())->getNameShort();
        	} else {
        		$person = CStaffManager::getPersonByPostId($headOfDepartment->getId())->getNameShort();
        	}
        }
        
        return $person;
    }
    
    /**
     * ФИО заведующего кафедрой
     *
     * @return string
     */
    public static function getHeadOfDepartment() {
        $headOfDepartment = CTaxonomyManager::getLegacyTaxonomy(TABLE_POSTS)->getTerm(CPostConstants::HEAD_OF_DEPARTMENT);
        $person = CStaffManager::getPersonByPostId($headOfDepartment->getId())->getNameShort();
        return $person;
    }
    
    /**
     * Должность заведующего кафедрой или исполняющего его обязанности
     *
     * @return string
     */
    public static function getPostHeadOfDepartment() {
        $post = "";
    	
        $headOfDepartment = CTaxonomyManager::getLegacyTaxonomy(TABLE_POSTS)->getTerm(CPostConstants::HEAD_OF_DEPARTMENT);
        $actingHeadOfDepartment = CTaxonomyManager::getLegacyTaxonomy(TABLE_POSTS)->getTerm(CPostConstants::ACTING_HEAD_OF_DEPARTMENT);
    	
        if (!is_null(CStaffManager::getPersonByPostId($actingHeadOfDepartment->getId()))) {
        	$post = $actingHeadOfDepartment->name_short;
        } else {
        	$post = $headOfDepartment->name_short;
        }
    	
        return $post;
    }
    
    /**
     * Действующие приказы для указанного года
     *
     * @param CPerson $person
     * @param CTerm $year
     * @return CArrayList
     */
    public static function getActiveOrdersForYear(CPerson $person, CTerm $year) {
        $result = new CArrayList();
        foreach ($person->orders->getItems() as $order) {
            if (CStaffService::orderIsActiveForYear($order, $year)) {
                $result->add($order->getId(), $order);
            }
        }
        return $result;
    }
    
    /**
     * Список действующих приказов для указанного года
     *
     * @param CPerson $person
     * @param CTerm $year
     * @return array
     */
    public static function getActiveOrdersListForYear(CPerson $person, CTerm $year) {
        $result = array();
        foreach (CStaffService::getActiveOrdersForYear($person, $year)->getItems() as $order) {
            $result[$order->getId()] = "Приказ № ".$order->num_order." от ".$order->date_order;
        }
        return $result;
    }
    
    /**
     * Действует ли приказ сотрудника для указанного года
     *
     * @param COrder $order
     * @param CTerm $year
     * @return bool
     */
    public static function orderIsActiveForYear(COrder $order, CTerm $year) {
        $dateStartYear = strtotime($year->date_start);
        $dateBeginOrder = strtotime($order->date_begin);
        $dateEndYear = strtotime($year->date_end);
        $dateEndOrder = strtotime($order->date_end);
        if ($dateBeginOrder < $dateEndYear and $dateStartYear < $dateEndOrder and $dateBeginOrder <= $dateEndYear) {
            return true;
        }
        return false;
    }
}