<?php

/**
 * Сервис по работе с протоколами кафедры
 *
 */
class CProtocolService {
	/**
	 * Список протоколов для выдачи тем курсовых проектов - протокол от даты выдачи +1 неделя (до ближайшего)
	 * 
	 * @param CCourseProject $courseProject
	 * @return array
	 */
    public static function getDepProtocolsListForIssuingThemesCourseProjects(CCourseProject $courseProject) {
    	$res = array();
        $arr = array();
        foreach (CProtocolManager::getAllDepProtocols()->getItems() as $i) {
        	$arr[$i->getId()] = date("Ymd", strtotime($i->getDate()));
        }
        arsort($arr, false);
        
        // дата выдачи темы курсового проекта
        $issueDate = strtotime($courseProject->issue_date);
        // дата выдачи темы курсового проекта +1 неделя
        $issueDatePlusOneWeek = strtotime("+1 week", $issueDate);
        
        $protocols = array();
        foreach ($arr as $key=>$value) {
        	$protocol = CProtocolManager::getDepProtocol($key);
        	$protocolDate = strtotime($protocol->getDate());
        	/**
        	 * Берём протокол, попавший в промежуток дат между датой выдачи курсового проекта +1 неделя
        	 */
        	if ($protocolDate >= $issueDate and $protocolDate <= $issueDatePlusOneWeek) {
        		$res[$protocol->getId()] = "Протокол №".$protocol->getNumber()." от ".date("d.m.Y", strtotime($protocol->getDate()));
        	}
        	/**
        	 * Отдельно берём все протоколы, начиная от даты выдачи темы
        	 */
        	if ($protocolDate >= $issueDate) {
        		$protocols[$protocol->getId()] = "Протокол №".$protocol->getNumber()." от ".date("d.m.Y", strtotime($protocol->getDate()));
        	}
        }
        /**
         * Если в промежутке между датой выдачи курсового проекта и +1 неделя нет протокола, 
         * берём протокол, ближайший к дате выдачи
         */
        if (empty($res)) {
        	$res[end(array_keys($protocols))] = end($protocols);
        }
        return $res;
    }
    
    /**
     * Список протоколов для хода работы курсовых проектов - протокол от даты выдачи +10 недель (до ближайшего)
     *
     * @param CCourseProject $courseProject
     * @return array
     */
    public static function getDepProtocolsListForProgressCourseProjects(CCourseProject $courseProject) {
    	$res = array();
    	$arr = array();
    	foreach (CProtocolManager::getAllDepProtocols()->getItems() as $i) {
    		$arr[$i->getId()] = date("Ymd", strtotime($i->getDate()));
    	}
    	arsort($arr, false);
    
    	// дата выдачи темы курсового проекта
    	$issueDate = strtotime($courseProject->issue_date);
    	// дата выдачи темы курсового проекта +10 недель
    	$issueDatePlusTenWeek = strtotime("+10 week", $issueDate);
    
    	$protocols = array();
    	foreach ($arr as $key=>$value) {
    		$protocol = CProtocolManager::getDepProtocol($key);
    		$protocolDate = strtotime($protocol->getDate());
    		/**
    		 * Берём протокол, попавший в промежуток дат между датой выдачи курсового проекта +1 неделя
    		 */
    		if ($protocolDate >= $issueDate and $protocolDate <= $issueDatePlusTenWeek) {
    			$res[$protocol->getId()] = "Протокол №".$protocol->getNumber()." от ".date("d.m.Y", strtotime($protocol->getDate()));
    		}
    		/**
    		 * Отдельно берём все протоколы, начиная от даты выдачи темы
    		 */
    		if ($protocolDate >= $issueDatePlusTenWeek) {
    			$protocols[$protocol->getId()] = "Протокол №".$protocol->getNumber()." от ".date("d.m.Y", strtotime($protocol->getDate()));
    		}
    	}
    	/**
    	 * Если в промежутке между датой выдачи курсового проекта и +10 недель нет протокола,
    	 * берём протокол, ближайший к дате выдачи
    	 */
    	if (empty($res)) {
    		$res[end(array_keys($protocols))] = end($protocols);
    	}
    	return $res;
    }
    
    /**
     * Список протоколов для результатов курсовых проектов - протокол от даты последней защиты (из журнала успеваемости)
     *
     * @param CCourseProject $courseProject
     * @return array
     */
    public static function getDepProtocolsListForResultsCourseProjects(CCourseProject $courseProject) {
    	$res = array();
    	$arr = array();
    	foreach (CProtocolManager::getAllDepProtocols()->getItems() as $i) {
    		$arr[$i->getId()] = date("Ymd", strtotime($i->getDate()));
    	}
    	arsort($arr, false);
    	
    	/**
    	 * Находим дату последней защиты в журнале успеваемости
    	 */
    	$dates = array();
    	foreach ($courseProject->tasks->getItems() as $task) {
    		if (!is_null(CStaffService::getStudentActivityByTypeAndDate($task->student, $task->courseProject->discipline, $task->courseProject->lecturer,
    				CTaxonomyManager::getLegacyTaxonomy("study_act")->getTerm(CCourseProjectConstants::CONTROL_TYPE_COURSE_PROJECT), $task->courseProject->issue_date))) {
    					$dates[] = strtotime(CStaffService::getStudentActivityByTypeAndDate($task->student, $task->courseProject->discipline, $task->courseProject->lecturer,
    							CTaxonomyManager::getLegacyTaxonomy("study_act")->getTerm(CCourseProjectConstants::CONTROL_TYPE_COURSE_PROJECT), $task->courseProject->issue_date)->date_act);
    				}
    	}
    	$lastDate = max($dates);
    
    	$protocols = array();
    	foreach ($arr as $key=>$value) {
    		$protocol = CProtocolManager::getDepProtocol($key);
    		$protocolDate = strtotime($protocol->getDate());
    		/**
    		 * Берём все протоколы, начиная от даты последней защиты в журнале успеваемости
    		 */
    		if ($protocolDate >= $lastDate) {
    			$protocols[$protocol->getId()] = "Протокол №".$protocol->getNumber()." от ".date("d.m.Y", strtotime($protocol->getDate()));
    		}
    	}
    	/**
    	 * Берём протокол, ближайший к дате последней защиты в журнале успеваемости
    	 */
    	$res[end(array_keys($protocols))] = end($protocols);
    	return $res;
    }
}