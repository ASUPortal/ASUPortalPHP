<?php

/**
 * Сервис по работе с сотрудниками
 *
 */
class CStaffService {
	
    /**
     * ФИО заведующего кафедрой или исполняющего его обязанности с добавлением названия должности, если $sign - true
     * 
     * @param boolean $sign
     * @return string
     */
    public static function getHeadOfDepartment($sign) {
        $person = "";
        
        $headOfDepartment = CTaxonomyManager::getLegacyTaxonomy("dolgnost")->getTerm("зав.каф.");
        $actingHeadOfDepartment = CTaxonomyManager::getLegacyTaxonomy("dolgnost")->getTerm("и.о. зав.каф.");
        
        if (!is_null(CStaffManager::getPersonByPostId($actingHeadOfDepartment->getId()))) {
        	if ($sign) {
        		$person = $actingHeadOfDepartment->name_short." ".CStaffManager::getPersonByPostId($actingHeadOfDepartment->getId())->getNameShort();
        	} else {
        		$person = CStaffManager::getPersonByPostId($actingHeadOfDepartment->getId())->getNameShort();
        	}
        } else {
        	if ($sign) {
        		$person = $headOfDepartment->name_short." ".CStaffManager::getPersonByPostId($headOfDepartment->getId())->getNameShort();
        	} else {
        		$person = CStaffManager::getPersonByPostId($headOfDepartment->getId())->getNameShort();
        	}
        }
        
        return $person;
    }
}