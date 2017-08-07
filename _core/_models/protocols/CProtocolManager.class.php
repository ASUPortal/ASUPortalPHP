<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.06.12
 * Time: 13:17
 * To change this template use File | Settings | File Templates.
 *
 * Менеджер протоколов разного вида
 */
class CProtocolManager {
    private static $_cacheDepProtocols = null;
    private static $_cacheSebProtocols = null;
    private static $_cacheSebProtocolsInit = false;
    private static $_cacheDepProtocolsInit = false;
    private static $_cacheNMSProtocols = null;
    private static $_cacheNMSProtocolPoints = null;
    private static $_cacheProtocolOpinions = null;
    /**
     * Кэш протоколов заседния кафедры
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheDepProtocols() {
        if (is_null(self::$_cacheDepProtocols)) {
            self::$_cacheDepProtocols = new CArrayList();
        }
        return self::$_cacheDepProtocols;
    }
    /**
     * Все протоколы заседаний кафедры
     *
     * @static
     * @return CArrayList
     */
    public static function getAllDepProtocols() {
        if (!self::$_cacheDepProtocolsInit) {
            self::$_cacheDepProtocolsInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_DEPARTMENT_PROTOCOLS)->getItems() as $i) {
                $protocol = new CDepartmentProtocol($i);
                self::getCacheDepProtocols()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheDepProtocols();
    }
    /**
     * Список протоколов для подстановки
     *
     * @static
     * @return array
     */
    public static function getAllDepProtocolsList() {
        $arr = array();
        $res = array();
        foreach (self::getAllDepProtocols()->getItems() as $i) {
            $arr[$i->getId()] = date("Ymd", strtotime($i->getDate()));
        }
        arsort($arr, false);
        foreach ($arr as $key=>$value) {
            $protocol = self::getDepProtocol($key);
            $res[$protocol->getId()] = "Протокол №".$protocol->getNumber()." от ".date("d.m.Y", strtotime($protocol->getDate()));
        }
        return $res;
    }
    /**
     * Протокол по ключу
     *
     * @static
     * @param $key
     * @return CDepartmentProtocol
     */
    public static function getDepProtocol($key) {
        if (!self::getCacheDepProtocols()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_DEPARTMENT_PROTOCOLS, $key);
            if (!is_null($ar)) {
                $protocol = new CDepartmentProtocol($ar);
                self::getCacheDepProtocols()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheDepProtocols()->getItem($key);
    }
    /**
     * Кэш протоколов ГАК
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheSebProtocols() {
        if (is_null(self::$_cacheSebProtocols)) {
            self::$_cacheSebProtocols = new CArrayList();
        }
        return self::$_cacheSebProtocols;
    }
    /**
     * Все протоколы ГАК
     *
     * @static
     * @return CArrayList
     */
    public static function getAllSebProtocols() {
        if (!self::$_cacheSebProtocolsInit) {
            self::$_cacheSebProtocolsInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_SEB_PROTOCOLS)->getItems() as $i) {
                $protocol = new CSEBProtocol($i);
                self::getCacheSebProtocols()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheSebProtocols();
    }
    /**
     * Протокол ГАК
     *
     * @static
     * @param $key
     * @return CSEBProtocol
     */
    public static function getSebProtocol($key) {
        if (!self::getCacheSebProtocols()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_SEB_PROTOCOLS, $key);
            if (!is_null($ar)) {
                $protocol = new CSEBProtocol($ar);
                self::getCacheSebProtocols()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheSebProtocols()->getItem($key);
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheNMSProtocols() {
        if (is_null(self::$_cacheNMSProtocols)) {
            self::$_cacheNMSProtocols = new CArrayList();
        }
        return self::$_cacheNMSProtocols;
    }

    /**
     * @param $key
     * @return CNMSProtocol
     */
    public static function getNMSProtocol($key) {
        if (!self::getCacheNMSProtocols()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_NMS_PROTOCOL, $key);
            if (!is_null($ar)) {
                $protocol = new CNMSProtocol($ar);
                self::getCacheNMSProtocols()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheNMSProtocols()->getItem($key);
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheNMSProtocolPoints() {
        if (is_null(self::$_cacheNMSProtocolPoints)) {
            self::$_cacheNMSProtocolPoints = new CArrayList();
        }
        return self::$_cacheNMSProtocolPoints;
    }

    /**
     * @param $key
     * @return CNMSProtocolAgendaPoint
     */
    public static function getNMSProtocolAgendaPoint($key) {
        if (!self::getCacheNMSProtocolPoints()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_NMS_PROTOCOL_AGENDA, $key);
            if (!is_null($ar)) {
                $protocol = new CNMSProtocolAgendaPoint($ar);
                self::getCacheNMSProtocolPoints()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheNMSProtocolPoints()->getItem($key);
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheProtocolOpinion() {
        if (is_null(self::$_cacheProtocolOpinions)) {
            self::$_cacheProtocolOpinions = new CArrayList();
        }
        return self::$_cacheProtocolOpinions;
    }

    /**
     * @param $key
     * @return CProtocolOpinion
     */
    public static function getProtocolOpinion($key) {
        if (!self::getCacheProtocolOpinion()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_PROTOCOL_OPINIONS, $key);
            if (!is_null($ar)) {
                $protocol = new CProtocolOpinion($ar);
                self::getCacheProtocolOpinion()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheProtocolOpinion()->getItem($key);
    }
    
    /**
     * @param $id
     * @param $field
     * @return string
     */
    public static function getFieldName($id, $field) {
        return "CModel[data][".$id."][".$field."]";
    }
    
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
    		 
    		$protocols = new CArrayList();
    		/**
    		 * Берём все протоколы, начиная от даты последней защиты в журнале успеваемости
    		 */
    		if (!empty($dates)) {
    			$lastDate = max($dates);
    			foreach (CActiveRecordProvider::getWithCondition(TABLE_DEPARTMENT_PROTOCOLS, 'date_text >= "'.date("Y-m-d", $lastDate).'"')->getItems() as $item) {
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
}
