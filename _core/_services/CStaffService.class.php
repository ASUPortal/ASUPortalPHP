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
}