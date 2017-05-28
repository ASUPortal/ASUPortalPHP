<?php

/**
 * Сервис по работе с учебной нагрузкой
 *
 */
class CStudyLoadService {
  
    /**
     * Учебная нагрузка
     *
     * @param $key
     * @return CStudyLoad
     */
    public static function getStudyLoad($key) {
        $ar = CActiveRecordProvider::getById(TABLE_WORKLOAD, $key);
        if (!is_null($ar)) {
            $studyLoad = new CStudyLoad($ar);
        }
        return $studyLoad;
    }
    
    /**
     * Удаление учебной нагрузки
     *
     * @param CStudyLoad $studyLoad
     */
    public static function deleteStudyLoad($studyLoad) {
    	$studyLoad->remove();
    }
    
    /**
     * Лист нагрузок преподавателя по году
     *
     * @param CPerson $person
     * @param CTerm $year
     *
     * @return CArrayList
     */
    public static function getStudyLoadsByYear(CPerson $person, CTerm $year) {
        $loads = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD, "person_id = ".$person->getId()." AND year_id = ".$year->getId())->getItems() as $item) {
            $study = new CStudyLoad($item);
            $loads->add($study->getId(), $study);
        }
        return $loads;
    }
    
    /**
     * Тип нагрузки из справочника учебных работ по названию
     * 
     * @param $nameHours
     * @return CStudyLoadWorkType
     */
    public static function getStudyLoadWorkTypeByNameHours($nameHours) {
    	$types = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD_WORK_TYPES, "name_hours_kind = ".$nameHours)->getItems() as $item) {
    		$type = new CStudyLoadWorkType($item);
    		$types->add($type->getId(), $type);
    	}
    	return $types->getFirstItem();
    }
    
    /**
     * Сотрудники с нагрузкой в указанном году
     *
     * @param int $isBudget
     * @param int $isContract
     * @param int $selectedYear
     * @return array
     */
    public static function getPersonsWithLoadByYear($isBudget, $isContract, $selectedYear) {
    	$personsWithLoad = array();
    	
    	// текущая дата для расчета ставки по актуальным приказам ОК
    	$dateFrom = date('Y.m.d', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
    	
    	if (!(!$isBudget and !$isContract)) {
    		$query = new CQuery();
    		$query->select("kadri.id as kadri_id,
						loads.year_id as year_id,
						kadri.fio as fio,
						kadri.fio_short,
						dolgnost.name_short as dolgnost,
						hr.rate")
    				->from(TABLE_PERSON." as kadri")
    				->leftJoin(TABLE_WORKLOAD." as loads", "loads.person_id = kadri.id")
    				->leftJoin(TABLE_WORKLOAD_WORKS." as hours", "hours.workload_id = loads.id")
    				->leftJoin(TABLE_POSTS." as dolgnost", "dolgnost.id = kadri.dolgnost")
    				->leftJoin(TABLE_HOURS_RATE." as hr", "hr.dolgnost_id = kadri.dolgnost")
    				->condition("loads.year_id = ".$selectedYear)
    				->group("kadri.id")
    				->order("kadri.fio_short asc");
    		$personsWithLoad = $query->execute()->getItems();
    		$i = 0;
    		foreach ($personsWithLoad as $person) {
    			$queryOrders = new CQuery();
    			$queryOrders->select("round(sum(rate),2) as rate_sum, count(id) as ord_cnt")
	    			->from(TABLE_STAFF_ORDERS." as orders")
	    			->condition('concat(substring(date_end, 7, 4), ".", substring(date_end, 4, 2), ".", substring(date_end, 1, 2)) >= "'.$dateFrom.'" and kadri_id = "'.$person['kadri_id'].'"');
    			foreach ($queryOrders->execute()->getItems() as $order) {
    				$personsWithLoad[$i]['rate_sum'] = $order['rate_sum'];
    				$personsWithLoad[$i]['ord_cnt'] = $order['ord_cnt'];
    				$i++;
    			}
    		}
    		$i = 0;
    		foreach ($personsWithLoad as $person) {
    			$groupsCountSum = 0;
    			$studentsCountSum = 0;
    			$lectsSum = 0;
    			$diplSum = 0;
    			$hoursSumBase = 0;
    			$hoursSumAdditional = 0;
    			$hoursSumPremium = 0;
    			$hoursSumHourly = 0;
    			$hoursSum = 0;
    			$year = CTaxonomyManager::getYear($selectedYear);
    			$studyLoads = CStudyLoadService::getStudyLoadsByYear(CStaffManager::getPerson($person['kadri_id']), $year);
    			foreach ($studyLoads as $studyLoad) {
    				$groupsCountSum += $studyLoad->groups_count;
    				$studentsCountSum += $studyLoad->students_count;
    				if ($isBudget) {
    					$kind = CTaxonomyManager::getTaxonomy("hours_kind")->getTerm("budgetStudyLoad")->getId();
    					foreach ($studyLoad->getWorksByKind($kind) as $work) {
    						$hoursSum += $work->workload;
    						if ($work->type_id == CStudyLoadWorkTypeIDConstants::LABOR_LECTURE) {
    							$lectsSum += $work->workload;
    						}
    						if ($work->type_id == CStudyLoadWorkTypeIDConstants::LABOR_DIPLOM_CONSULTATION) {
    							$diplSum += $work->workload;
    						}
    						if ($work->studyLoad->load_type_id == CStudyLoadTypeIDConstants::MAIN) {
    							$hoursSumBase += $work->workload;
    						}
    						if ($work->studyLoad->load_type_id == CStudyLoadTypeIDConstants::ADDITIONAL) {
    							$hoursSumAdditional += $work->workload;
    						}
    						if ($work->studyLoad->load_type_id == CStudyLoadTypeIDConstants::PREMIUM) {
    							$hoursSumPremium += $work->workload;
    						}
    						if ($work->studyLoad->load_type_id == CStudyLoadTypeIDConstants::BY_TIME) {
    							$hoursSumHourly += $work->workload;
    						}
    					}
    				}
    				if ($isContract) {
    					$kind = CTaxonomyManager::getTaxonomy("hours_kind")->getTerm("contractStudyLoad")->getId();
    					foreach ($studyLoad->getWorksByKind($kind) as $work) {
    						$hoursSum += $work->workload;
    						if ($work->type_id == CStudyLoadWorkTypeIDConstants::LABOR_LECTURE) {
    							$lectsSum += $work->workload;
    						}
    						if ($work->type_id == CStudyLoadWorkTypeIDConstants::LABOR_DIPLOM_CONSULTATION) {
    							$diplSum += $work->workload;
    						}
    						if ($work->studyLoad->load_type_id == CStudyLoadTypeIDConstants::MAIN) {
    							$hoursSumBase += $work->workload;
    						}
    						if ($work->studyLoad->load_type_id == CStudyLoadTypeIDConstants::ADDITIONAL) {
    							$hoursSumAdditional += $work->workload;
    						}
    						if ($work->studyLoad->load_type_id == CStudyLoadTypeIDConstants::PREMIUM) {
    							$hoursSumPremium += $work->workload;
    						}
    						if ($work->studyLoad->load_type_id == CStudyLoadTypeIDConstants::BY_TIME) {
    							$hoursSumHourly += $work->workload;
    						}
    					}
    				}
    			}
    			
    			$personsWithLoad[$i]['groups_cnt_sum_'] = $groupsCountSum;
    			$personsWithLoad[$i]['stud_cnt_sum_'] = $studentsCountSum;
    			$personsWithLoad[$i]['lects_sum_'] = $lectsSum;
    			$personsWithLoad[$i]['dipl_sum_'] = $diplSum;
    			$personsWithLoad[$i]['hours_sum1_'] = $hoursSumBase;
    			$personsWithLoad[$i]['hours_sum2_'] = $hoursSumAdditional;
    			$personsWithLoad[$i]['hours_sum3_'] = $hoursSumPremium;
    			$personsWithLoad[$i]['hours_sum4_'] = $hoursSumHourly;
    			$personsWithLoad[$i]['hours_sum'] = $hoursSum;
    			
    			$i++;
    		}
    	}
    	return $personsWithLoad;
    }
    
    /**
     * Сотрудники без нагрузки в указанном году
     *
     * @param int $selectedYear
     * @return CArrayList
     */
    public static function getPersonsWithoutLoadByYear($selectedYear) {
    	$personsWithoutLoad = new CArrayList();
    	$query = new CQuery();
    	$query->select("person.*")
	    	->from(TABLE_PERSON." as person")
	    	->innerJoin(TABLE_PERSON_BY_TYPES." as types", "types.kadri_id = person.id")
	    	->innerJoin(TABLE_TYPES." as person_types", "person_types.id = types.person_type_id")
	    	->condition("person_types.name_short like '%ППС%' and person.id NOT IN (SELECT person_id from ".TABLE_WORKLOAD." WHERE year_id='".$selectedYear."')")
	    	->order("person.fio_short asc");
    	
    	$set = new CRecordSet(false);
    	$set->setQuery($query);
    	foreach ($set->getItems() as $item) {
    		$person = new CPerson($item);
    		$personsWithoutLoad->add($person->getId(), $person);
    	}
    	
    	return $personsWithoutLoad;
    }
    
    /**
     * Лист нагрузок преподавателя по году и семестру
     * (1 - осенний, 2 - весенний)
     *
     * @param CArrayList $loads
     * @param int $part
     *
     * @return CArrayList
     */
    public static function getStudyLoadsByPart($loads, $part) {
    	$result = new CArrayList();
    	foreach ($loads as $study) {
    		if ($study->year_part_id == $part) {
    			$result->add($study->getId(), $study);
    		}
    	}
    	return $result;
    }
}