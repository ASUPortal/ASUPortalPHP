<?php

class CStudyLoadParameters extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Параметры, необходимые для получения списка учебных нагрузок";
    }

    public function getFieldDescription()
    {
        return "Используется при печати учебной нагрузки, принимает значения параметров учебной нагрузки";
    }
    
    public function getParentClassField()
    {
    
    }
    
    public function getFieldType()
    {
    	
    }
    
    public function execute($contextObject)
    {

    }
    
    /**
     * Преподаватель учебной нагрузки
     * 
     * @return CPerson
     */
    public function getLecturer()
    {
    	$lecturer = CStaffManager::getPerson(CRequest::getInt("kadri_id"));
    	if (is_null($lecturer)) {
    		$params = unserialize(urldecode(CRequest::getString("id")));
    		if (array_key_exists("kadri_id", $params)) {
    			$lecturer = CStaffManager::getPerson($params["kadri_id"]);
    		}
    	}
    	return $lecturer;
    }
    
    /**
     * Год учебной нагрузки
     * 
     * @return CTerm
     */
    public function getYear()
    {
    	$year = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
    	if (is_null($year)) {
    		$params = unserialize(urldecode(CRequest::getString("id")));
    		if (array_key_exists("year_id", $params)) {
    			$year = CTaxonomyManager::getYear($params["year_id"]);
    		}
    	}
    	return $year;
    }
    
    /**
     * Типы учебной нагрузки (основная, дополнительная, надбавка, почасовка)
     * 
     * @return array $loadTypes
     */
    public function getLoadTypes()
    {
    	$loadTypes = array();
    	$params = unserialize(urldecode(CRequest::getString("id")));
    	
    	if (CRequest::getInt("base") != 0) {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::BASE)->getId();
    	} elseif (array_key_exists("base", $params)) {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::BASE)->getId();
    	}
    	
    	if (CRequest::getInt("additional") != 0) {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::ADDITIONAL)->getId();
    	} elseif (array_key_exists("additional", $params)) {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::ADDITIONAL)->getId();
    	}
    	
    	if (CRequest::getInt("premium") != 0) {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::PREMIUM)->getId();
    	} elseif (array_key_exists("premium", $params)) {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::PREMIUM)->getId();
    	}
    	
    	if (CRequest::getInt("byTime") != 0) {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::BY_TIME)->getId();
    	} elseif (array_key_exists("byTime", $params)) {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::BY_TIME)->getId();
    	}
    	
    	return $loadTypes;
    }
}