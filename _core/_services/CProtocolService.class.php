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
    public static function getIssueProtocolsList(CCourseProject $courseProject) {
    	$res = array();
    	
    	// дата выдачи темы курсового проекта
    	$issueDate = strtotime($courseProject->issue_date);
    	// дата выдачи темы курсового проекта +1 неделя
    	$issueDatePlusOneWeek = strtotime("+1 week", $issueDate);
    	
    	if (!is_null($courseProject->getId())) {
    		$protocols = new CArrayList();
    		/**
    		 * Берём протокол, попавший в промежуток дат между датой выдачи курсового проекта +1 неделя
    		 */
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_DEPARTMENT_PROTOCOLS, 'date_text between "'.date("Y-m-d", $issueDate).'" and "'.date("Y-m-d", $issueDatePlusOneWeek).'"')->getItems() as $item) {
    			$protocol = new CDepartmentProtocol($item);
    			$protocols->add($protocol->getId(), $protocol);
    		}
    		foreach ($protocols as $protocol) {
    			$res[$protocol->getId()] = "Протокол №".$protocol->getNumber()." от ".date("d.m.Y", strtotime($protocol->getDate()));
    		}
    		/**
    		 * Если в промежутке между датой выдачи курсового проекта и +1 неделя нет протокола,
    		 * берём протокол, ближайший к дате выдачи
    		 */
    		if ($protocols->isEmpty()) {
    			foreach (CActiveRecordProvider::getWithCondition(TABLE_DEPARTMENT_PROTOCOLS, 'date_text >= "'.date("Y-m-d", $issueDate).'"')->getItems() as $item) {
    				$protocol = new CDepartmentProtocol($item);
    				$protocols->add($protocol->getId(), $protocol);
    			}
    			/**
    			 * Отсортируем протоколы по датам
    			 */
    			$arr = array();
    			foreach ($protocols as $protocol) {
    				$arr[$protocol->getId()] = date("Ymd", strtotime($protocol->getDate()));
    			}
    			arsort($arr, false);
    			/**
    			 * Получим протокол, ближайший к дате выдачи
    			 */
    			$protocolsNext = array();
    			foreach ($arr as $key=>$value) {
    				$protocolNext = CProtocolManager::getDepProtocol($key);
    				$protocolsNext[$protocolNext->getId()] = "Протокол №".$protocolNext->getNumber()." от ".date("d.m.Y", strtotime($protocolNext->getDate()));
    			}
    			$res[end(array_keys($protocolsNext))] = end($protocolsNext);
    		}
    	}
        return $res;
    }
    
    /**
     * Список протоколов для хода работы курсовых проектов - протокол от даты выдачи +10 недель (до ближайшего)
     *
     * @param CCourseProject $courseProject
     * @return array
     */
    public static function getProgressProtocolsList(CCourseProject $courseProject) {
    	$res = array();
    	
    	// дата выдачи темы курсового проекта
    	$issueDate = strtotime($courseProject->issue_date);
    	// дата выдачи темы курсового проекта +10 недель
    	$issueDatePlusTenWeek = strtotime("+10 week", $issueDate);
    	
    	if (!is_null($courseProject->getId())) {
    		$protocols = new CArrayList();
    		/**
    		 * Берём протокол, попавший в промежуток дат между датой выдачи курсового проекта +1 неделя
    		*/
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_DEPARTMENT_PROTOCOLS, 'date_text between "'.date("Y-m-d", $issueDate).'" and "'.date("Y-m-d", $issueDatePlusTenWeek).'"')->getItems() as $item) {
    			$protocol = new CDepartmentProtocol($item);
    			$protocols->add($protocol->getId(), $protocol);
    		}
    		foreach ($protocols as $protocol) {
    			$res[$protocol->getId()] = "Протокол №".$protocol->getNumber()." от ".date("d.m.Y", strtotime($protocol->getDate()));
    		}
    		/**
    		 * Если в промежутке между датой выдачи курсового проекта и +10 недель нет протокола,
    		 * берём протокол, ближайший к дате выдачи
    		 */
    		if ($protocols->isEmpty()) {
    			foreach (CActiveRecordProvider::getWithCondition(TABLE_DEPARTMENT_PROTOCOLS, 'date_text >= "'.date("Y-m-d", $issueDate).'"')->getItems() as $item) {
    				$protocol = new CDepartmentProtocol($item);
    				$protocols->add($protocol->getId(), $protocol);
    			}
    			/**
    			 * Отсортируем протоколы по датам
    			 */
    			$arr = array();
    			foreach ($protocols as $protocol) {
    				$arr[$protocol->getId()] = date("Ymd", strtotime($protocol->getDate()));
    			}
    			arsort($arr, false);
    			/**
    			 * Получим протокол, ближайший к дате выдачи
    			*/
    			$protocolsNext = array();
    			foreach ($arr as $key=>$value) {
    				$protocolNext = CProtocolManager::getDepProtocol($key);
    				$protocolsNext[$protocolNext->getId()] = "Протокол №".$protocolNext->getNumber()." от ".date("d.m.Y", strtotime($protocolNext->getDate()));
    			}
    			$res[end(array_keys($protocolsNext))] = end($protocolsNext);
    		}
    	}
    	return $res;
    }
    
    /**
     * Список протоколов для результатов курсовых проектов - протокол от даты последней защиты (из журнала успеваемости)
     *
     * @param CCourseProject $courseProject
     * @return array
     */
    public static function getResultsProtocolsList(CCourseProject $courseProject) {
    	$res = array();
    	if (!is_null($courseProject->getId())) {
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
    		 
    		$protocols = array();
    		if (!empty($dates)) {
    			$lastDate = max($dates);
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
    		}
    	}
    	return $res;
    }
}