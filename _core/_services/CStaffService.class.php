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
        
        $headOfDepartment = CTaxonomyManager::getLegacyTaxonomy(TABLE_POSTS)->getTerm("head_of_department");
        $actingHeadOfDepartment = CTaxonomyManager::getLegacyTaxonomy(TABLE_POSTS)->getTerm("acting_head_of_department");
        
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
        $headOfDepartment = CTaxonomyManager::getLegacyTaxonomy(TABLE_POSTS)->getTerm("head_of_department");
        $person = CStaffManager::getPersonByPostId($headOfDepartment->getId())->getNameShort();
        return $person;
    }
}