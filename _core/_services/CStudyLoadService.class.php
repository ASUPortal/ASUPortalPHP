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
        $studyLoad = null;
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
    public static function deleteStudyLoad(CStudyLoad $studyLoad) {
    	// удаляем данные по значениям видов работ нагрузки
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD_WORKS, "workload_id=".$studyLoad->getId())->getItems() as $ar) {
    		$ar->remove();
    	}
    	// удаляем данные по студенческим группам
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD_STUDY_GROUPS, "workload_id=".$studyLoad->getId())->getItems() as $ar) {
    		$ar->remove();
    	}
    	// удаляем саму нагрузку
    	$studyLoad->remove();
    }
    
    /**
     * Лист нагрузок преподавателя по году
     *
     * @param CPerson $person
     * @param CTerm $year
     * @return CArrayList
     */
    public static function getStudyLoadsByYear(CPerson $person, CTerm $year) {
        $loads = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD, "person_id = ".$person->getId()." AND year_id = ".$year->getId()." AND _is_last_version = 1")->getItems() as $item) {
            $study = new CStudyLoad($item);
            $loads->add($study->getId(), $study);
        }
        return $loads;
    }
    
    /**
     * Лист нагрузок всех преподавателей по году
     *
     * @param CTerm $year
     * @return CArrayList
     */
    public static function getAllStudyLoadsByYear(CTerm $year) {
    	$loads = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD, "year_id = ".$year->getId()." AND _is_last_version = 1")->getItems() as $item) {
    		$study = new CStudyLoad($item);
    		$loads->add($study->getId(), $study);
    	}
    	return $loads;
    }
    
    /**
     * Лист нагрузок преподавателя по году
     *
     * @param CPerson $person - преподаватель
     * @param CTerm $year - учебный год
     * @return CArrayList
     */
    public static function getAllStudyLoadsByYearAndPerson(CPerson $person, CTerm $year) {
    	$loads = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD, "person_id = ".$person->getId()." AND year_id = ".$year->getId()." AND _is_last_version = 1")->getItems() as $item) {
    		$study = new CStudyLoad($item);
    		$loads->add($study->getId(), $study);
    	}
    	return $loads;
    }
    
    /**
     * Лист нагрузок преподавателя по году и типу нагрузки
     *
     * @param CPerson $person - преподаватель
     * @param CTerm $year - учебный год
     * @param array $loadTypes - типы нагрузок
     * @return CArrayList
     */
    public static function getStudyLoadsByYearAndLoadType(CPerson $person, CTerm $year, $loadTypes) {
    	$loads = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD, "person_id = ".$person->getId()." AND year_id = ".$year->getId()." AND load_type_id IN (".implode($loadTypes, ", ").") AND _is_last_version = 1")->getItems() as $item) {
    		$study = new CStudyLoad($item);
    		$loads->add($study->getId(), $study);
    	}
    	return $loads;
    }
    
    /**
     * Список преподавателей, у которых есть нагрузка по дисциплине
     *
     * @param CTerm $discipline
     * @return CArrayList
     */
    public static function getLecturersNameByDiscipline(CTerm $discipline) {
    	$lecturers = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD, "discipline_id = ".$discipline->getId()." AND _is_last_version = 1")->getItems() as $item) {
    		$study = new CStudyLoad($item);
    		$lecturers->add($study->lecturer->getId(), $study->lecturer);
    	}
    	$comparator = new CDefaultComparator("fio");
    	$sorted = CCollectionUtils::sort($lecturers, $comparator);
    	return $sorted;
    }
    
    /**
     * Информация по дипломникам преподавателя по году нагрузки
     *
     * @param CPerson $lecturer
     * @param CTerm $year
     * @return array
     */
    public static function getDiplomsInfo(CPerson $lecturer, CTerm $year) {
    	$diploms = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_DIPLOMS, '(date_act >= "'.$year->date_start.'" and date_act <= "'.$year->date_end.'")
    			and kadri_id = '.$lecturer->getId())->getItems() as $item) {
    		$diplom = new CDiplom($item);
    		$diploms->add($diplom->getId(), $diplom);
    	}
    	$info = array();
    	foreach ($diploms->getItems() as $diplom) {
    		$fio = "";
    		$practPlace = "";
    		$group = "";
    		if (!is_null($diplom->student)) {
    			$fio = $diplom->student->getName();
    			if (!is_null($diplom->student->getGroup())) {
    				$group = $diplom->student->getGroup()->getName();
    			}
    			if (is_null($diplom->practPlace)) {
    				$practPlace = $diplom->pract_place;
    			} else {
    				$practPlace = $diplom->practPlace->getValue();
    			}
    		}
    		$string = $fio." - ".$practPlace." - ".$group;
    		// замена кавычек для отображения во всплывающей подсказке
    		$info[] = htmlspecialchars($string);
    	}
    	return $info;
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
     * @param int $isBudget - вид работы нагрузки: бюджет
     * @param int $isContract - вид работы нагрузки: контракт
     * @param CTerm $year - выбранный учебный год
     * @return CArrayList
     */
    public static function getPersonsWithLoadByYear($isBudget, $isContract, $year, $person = null) {
    	// id типов учебной нагрузки (основная, дополнительная, надбавка, почасовка)
    	$baseLoadId = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::BASE)->getId();
    	$additionalLoadId = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::ADDITIONAL)->getId();
    	$premiumLoadId = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::PREMIUM)->getId();
    	$byTimeLoadId = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::BY_TIME)->getId();
    	
    	$personsWithLoad = new CArrayList();
    	if ($isBudget or $isContract) {
    		$query = new CQuery();
    		$query->select("kadri.id as kadri_id,
    						loads.year_id as year_id,
    						kadri.fio as fio,
    						kadri.fio_short,
    						dolgnost.name_short as dolgnost,
    						dolgnost.id as dolgnostId");
    		$query->from(TABLE_PERSON." as kadri");
    		$query->leftJoin(TABLE_WORKLOAD." as loads", "loads.person_id = kadri.id");
    		$query->leftJoin(TABLE_WORKLOAD_WORKS." as hours", "hours.workload_id = loads.id");
    		$query->leftJoin(TABLE_POSTS." as dolgnost", "dolgnost.id = kadri.dolgnost");
    		if (!is_null($person)) {
    			$query->condition("loads.year_id = ".$year->getId()." and loads.person_id = ".$person->getId()." AND loads._is_last_version = 1");
    		} else {
    			$query->condition("loads.year_id = ".$year->getId()." AND loads._is_last_version = 1");
    		}
    		$query->group("kadri.id");
    		$query->order("kadri.fio asc");
    		
    		// дисциплина "Дипломное проектирование"
    		$discipline = CTaxonomyManager::getLegacyTaxonomy(TABLE_DISCIPLINES)->getTerm(CStudyLoadDisciplineConstants::DIPLOM_PROJECT);
    		$partFall = CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::FALL);
    		$partSpring = CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::SPRING);
    		
    		foreach ($query->execute()->getItems() as $personLoad) {
    			$person = CStaffManager::getPerson($personLoad['kadri_id']);
    			
    			$row = new CStudyLoadReportRow();
    			$row->personId = $personLoad['kadri_id'];
    			$row->yearId = $personLoad['year_id'];
    			$row->personName = $personLoad['fio'];
    			$row->personShortName = $personLoad['fio_short'];
    			$row->personPost = $personLoad['dolgnost'];
    			$row->personPostId = $personLoad['dolgnostId'];
    			$row->rateSum = $person->getOrdersRate();
    			$row->orderCount = $person->getOrdersCount();
    			
    			$groupsCountSum = 0;
    			$studentsCountSum = 0;
    			$hoursSumBase = 0;
    			$hoursSumAdditional = 0;
    			$hoursSumPremium = 0;
    			$hoursSumByTime = 0;
    			$hoursSum = 0;
    			 
    			$diplCountWinter = 0;
    			$diplCountSummer = 0;

    			$studyLoads = CStudyLoadService::getStudyLoadsByYear($person, $year);
    			foreach ($studyLoads->getItems() as $studyLoad) {
    				$groupsCountSum += $studyLoad->groups_count;
    				if ($isBudget) {
    					$studentsCountSum += $studyLoad->students_count;
    					$kind = CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::BUDGET)->getId();
    					foreach ($studyLoad->getWorksByKind($kind) as $work) {
    						$hoursSum += $work->workload;
    						$hoursSumBase += $work->getSumWorkHoursByLoadTypeId($baseLoadId);
    						$hoursSumAdditional += $work->getSumWorkHoursByLoadTypeId($additionalLoadId);
    						$hoursSumPremium += $work->getSumWorkHoursByLoadTypeId($premiumLoadId);
    						$hoursSumByTime += $work->getSumWorkHoursByLoadTypeId($byTimeLoadId);
    					}
    						
    					// количество дипломников по дисциплине "Консультация, руководство ВКР" (бюджет)
    					$diplCountWinter += CStudyLoadService::getStudentsCountFromLoadByDisciplineAndPart($studyLoad, $discipline, $partFall, $kind);
    					$diplCountSummer += CStudyLoadService::getStudentsCountFromLoadByDisciplineAndPart($studyLoad, $discipline, $partSpring, $kind);
    				}
    				if ($isContract) {
    					$studentsCountSum += $studyLoad->students_contract_count;
    					$kind = CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::CONTRACT)->getId();
    					foreach ($studyLoad->getWorksByKind($kind) as $work) {
    						$hoursSum += $work->workload;
    						$hoursSumBase += $work->getSumWorkHoursByLoadTypeId($baseLoadId);
    						$hoursSumAdditional += $work->getSumWorkHoursByLoadTypeId($additionalLoadId);
    						$hoursSumPremium += $work->getSumWorkHoursByLoadTypeId($premiumLoadId);
    						$hoursSumByTime += $work->getSumWorkHoursByLoadTypeId($byTimeLoadId);
    					}
    						
    					// количество дипломников по дисциплине "Консультация, руководство ВКР" (контракт)
    					$diplCountWinter += CStudyLoadService::getStudentsCountFromLoadByDisciplineAndPart($studyLoad, $discipline, $partFall, $kind);
    					$diplCountSummer += CStudyLoadService::getStudentsCountFromLoadByDisciplineAndPart($studyLoad, $discipline, $partSpring, $kind);
    				}
    			}
    			$row->groupsCountSum = $groupsCountSum;
    			$row->studentsCountSum = $studentsCountSum;
    			$row->hoursSumBase = $hoursSumBase;
    			$row->hoursSumAdditional = $hoursSumAdditional;
    			$row->hoursSumPremium = $hoursSumPremium;
    			$row->hoursSumByTime = $hoursSumByTime;
    			$row->workloadSum = $hoursSum;
    			
    			$row->diplCountWinter = $diplCountWinter;
    			$row->diplCountSummer = $diplCountSummer;
    			
    			$personsWithLoad->add($row->personId, $row);
    		}
    	}
    	return $personsWithLoad;
    }
    
    /**
     * Значения для общей суммы по типам нагрузки по преподавателю
     * 
     * @param int $kadriId - id преподавателя
     * @param int $yearId - id года
     * @param int $isBudget - вид работы нагрузки: бюджет
     * @param int $isContract - вид работы нагрузки: контракт
     * @return array
     */
    public static function getStudyWorksTotalValues($kadriId, $yearId, $isBudget, $isContract) {
    	$result = array();
    	foreach (CTaxonomyManager::getLegacyTaxonomy(TABLE_WORKLOAD_WORK_TYPES)->getTerms()->getItems() as $term) {
    		if ($term->is_total) {
    			$row = array();
    			
    			// тип работы
    			$row[0] = $term->getValue();
    			
    			$person = CStaffManager::getPerson($kadriId);
    			$year = CTaxonomyManager::getYear($yearId);
    			$sum = 0;
    			
    			// бюджет
    			if ($isBudget and !$isContract) {
    				foreach (CStudyLoadService::getStudyLoadsByYear($person, $year)->getItems() as $studyLoad) {
    					$sum += $studyLoad->getLoadByKindAndType(CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::BUDGET)->getId(), $term->getId());
    				}
    			}
    			 
    			// коммерция
    			if ($isContract and !$isBudget) {
    				foreach (CStudyLoadService::getStudyLoadsByYear($person, $year)->getItems() as $studyLoad) {
    					$sum += $studyLoad->getLoadByKindAndType(CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::CONTRACT)->getId(), $term->getId());
    				}
    			}
    			 
    			// бюджет и коммерция
    			if ($isContract and $isBudget) {
    				foreach (CStudyLoadService::getStudyLoadsByYear($person, $year)->getItems() as $studyLoad) {
    					$sum += $studyLoad->getLoadByType($term->getId());
    				}
    			}
    			
    			$row[1] = $sum;
    			 
    			$result[$term->getId()] = $row;
    		}
    	}
    	return $result;
    }
    
    /**
     * Значения для общей суммы по преподавателю и семестру
     *
     * @param CPerson $lecturer - преподаватель
     * @param CTerm $year - учебный год
     * @param CYearPart $part - семестр
     * @param array $loadTypes - типы нагрузок
     * 
     * @return CArrayList
     */
    public static function getStudyWorksTotalValuesByLecturerAndPart(CPerson $lecturer, CTerm $year, CYearPart $part, $loadTypes) {
    	$result = new CArrayList();
    	foreach (CTaxonomyManager::getLegacyTaxonomy(TABLE_WORKLOAD_WORK_TYPES)->getTerms()->getItems() as $term) {
    		$row = array();
    		
    		// тип работы
    		$row[0] = $term->getValue();
    		
    		$sum = 0;
    		foreach (CStudyLoadService::getStudyLoadsByYearAndLoadType($lecturer, $year, $loadTypes)->getItems() as $studyLoad) {
    			if ($studyLoad->year_part_id == $part->getId()) {
    				$sum += $studyLoad->getLoadByType($term->getId());
    			}
    		}
    		
    		$row[1] = $sum;
    		
    		$result->add($term->getId(), $row);
    	}
    	return $result;
    }
    

    /**
     * Значения для общей суммы по преподавателю за оба семестра
     *
     * @param CPerson $lecturer - преподаватель
     * @param CTerm $year - учебный год
     * @param array $loadTypes - типы нагрузок
     *
     * @return CArrayList
     */
    public static function getStudyWorksTotalValuesByLecturer(CPerson $lecturer, CTerm $year, $loadTypes) {
    	$result = new CArrayList();
    	foreach (CTaxonomyManager::getLegacyTaxonomy(TABLE_WORKLOAD_WORK_TYPES)->getTerms()->getItems() as $term) {
    		$row = array();
    
    		// тип работы
    		$row[0] = $term->getValue();
    
    		$sum = 0;
    		foreach (CStudyLoadService::getStudyLoadsByYearAndLoadType($lecturer, $year, $loadTypes)->getItems() as $studyLoad) {
    			$sum += $studyLoad->getLoadByType($term->getId());
    		}
    
    		$row[1] = $sum;
    
    		$result->add($term->getId(), $row);
    	}
    	return $result;
    }
    
    /**
     * Значения для столбца "Всего" по преподавателю, году, семестру и типам нагрузок
     *
     * @param CPerson $lecturer - преподаватель
     * @param CTerm $year - учебный год
     * @param CYearPart $part - семестр
     * @param array $loadTypes - типы нагрузок
     *
     * @return int
     */
    public static function getAllStudyWorksTotalValuesByLecturerAndPart(CPerson $lecturer, CTerm $year, CYearPart $part, $loadTypes) {
    	$sum = 0;
    	foreach (CStudyLoadService::getStudyLoadsByYearAndLoadType($lecturer, $year, $loadTypes)->getItems() as $studyLoad) {
    		if ($studyLoad->year_part_id == $part->getId()) {
    			$sum += $studyLoad->getSumWorksValue();
    		}
    	}
    	return $sum;
    }
    
    /**
     * Значения для столбца "Надбавка за филиалы" по преподавателю, году, семестру и типам нагрузок
     *
     * @param CPerson $lecturer - преподаватель
     * @param CTerm $year - учебный год
     * @param CYearPart $part - семестр
     * @param array $loadTypes - типы нагрузок
     *
     * @return int
     */
    public static function getAllStudyWorksTotalValuesByLecturerAndPartWithFilials(CPerson $lecturer, CTerm $year, CYearPart $part, $loadTypes) {
    	$sum = 0;
    	foreach (CStudyLoadService::getStudyLoadsByYearAndLoadType($lecturer, $year, $loadTypes)->getItems() as $studyLoad) {
    		if ($studyLoad->year_part_id == $part->getId()) {
    			$sum += $studyLoad->getWorkWithFilialsTotals();
    		}
    	}
    	return $sum;
    }
    
    /**
     * Значение "Всего за год" (без учёта филиалов) по преподавателю, году и типам нагрузок
     *
     * @param CPerson $lecturer - преподаватель
     * @param CTerm $year - учебный год
     * @param array $loadTypes - типы нагрузок
     *
     * @return int
     */
    public static function getAllStudyWorksTotalValuesByLecturer(CPerson $lecturer, CTerm $year, $loadTypes) {
    	$sum = 0;
    	foreach (CStudyLoadService::getStudyLoadsByYearAndLoadType($lecturer, $year, $loadTypes)->getItems() as $studyLoad) {
    		$sum += $studyLoad->getSumWorksValue();
    	}
    	return $sum;
    }
    
    /**
     * Значение "Всего за год" (с учётом филиалов) по преподавателю, году и типам нагрузок
     *
     * @param CPerson $lecturer - преподаватель
     * @param CTerm $year - учебный год
     * @param array $loadTypes - типы нагрузок
     *
     * @return int
     */
    public static function getAllStudyWorksTotalValuesByLecturerWithFilials(CPerson $lecturer, CTerm $year, $loadTypes) {
    	$sum = 0;
    	foreach (CStudyLoadService::getStudyLoadsByYearAndLoadType($lecturer, $year, $loadTypes)->getItems() as $studyLoad) {
    		$sum += $studyLoad->getSumWorksValue();
    		$sum += $studyLoad->getWorkWithFilialsTotals();
    	}
    	return $sum;
    }
    
    /**
     * Значения для общей суммы по типам нагрузки по всем преподавателям
     *
     * @param $yearId - id года
     * @param int $isBudget - вид работы нагрузки: бюджет
     * @param int $isContract - вид работы нагрузки: контракт
     * @return CArrayList
     */
    public static function getAllStudyWorksTotalValues($yearId, $isBudget, $isContract) {
    	$result = new CArrayList();
    	foreach (CTaxonomyManager::getLegacyTaxonomy(TABLE_WORKLOAD_WORK_TYPES)->getTerms()->getItems() as $term) {
    		if ($term->is_total) {
    			$row = array();
    			 
    			// тип работы
    			$row[0] = $term->getValue();
    			
    			$year = CTaxonomyManager::getYear($yearId);
    			$sum = 0;
    			 
    			// бюджет
    			if ($isBudget and !$isContract) {
    				foreach (CStudyLoadService::getAllStudyLoadsByYear($year)->getItems() as $studyLoad) {
    					$sum += $studyLoad->getLoadByKindAndType(CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::BUDGET)->getId(), $term->getId());
    				}
    			}
    	
    			// коммерция
    			if ($isContract and !$isBudget) {
    				foreach (CStudyLoadService::getAllStudyLoadsByYear($year)->getItems() as $studyLoad) {
    					$sum += $studyLoad->getLoadByKindAndType(CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::CONTRACT)->getId(), $term->getId());
    				}
    			}
    			
    			// бюджет и коммерция
    			if ($isContract and $isBudget) {
    				foreach (CStudyLoadService::getAllStudyLoadsByYear($year)->getItems() as $studyLoad) {
    					$sum += $studyLoad->getLoadByType($term->getId());
    				}
    			}
    			$row[1] = $sum;
    	
    			$result->add($term->getId(), $row);
    		}
    	}
    	return $result;
    }
    
    /**
     * Значения для общей суммы по типам нагрузки по одному преподавателю
     *
     * @param $personId - id преподавателя
     * @param $yearId - id года
     * @param int $isBudget - вид работы нагрузки: бюджет
     * @param int $isContract - вид работы нагрузки: контракт
     * @return CArrayList
     */
    public static function getAllStudyWorksTotalValuesByPerson($personId, $yearId, $isBudget, $isContract) {
    	$result = new CArrayList();
    	foreach (CTaxonomyManager::getLegacyTaxonomy(TABLE_WORKLOAD_WORK_TYPES)->getTerms()->getItems() as $term) {
    		if ($term->is_total) {
    			$row = array();
    
    			// тип работы
    			$row[0] = $term->getValue();
    			 
    			$person = CStaffManager::getPerson($personId);
    			$year = CTaxonomyManager::getYear($yearId);
    			$sum = 0;
    
    			// бюджет
    			if ($isBudget and !$isContract) {
    				foreach (CStudyLoadService::getAllStudyLoadsByYearAndPerson($person, $year)->getItems() as $studyLoad) {
    					$sum += $studyLoad->getLoadByKindAndType(CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::BUDGET)->getId(), $term->getId());
    				}
    			}
    			 
    			// коммерция
    			if ($isContract and !$isBudget) {
    				foreach (CStudyLoadService::getAllStudyLoadsByYearAndPerson($person, $year)->getItems() as $studyLoad) {
    					$sum += $studyLoad->getLoadByKindAndType(CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::CONTRACT)->getId(), $term->getId());
    				}
    			}
    			 
    			// бюджет и коммерция
    			if ($isContract and $isBudget) {
    				foreach (CStudyLoadService::getAllStudyLoadsByYearAndPerson($person, $year)->getItems() as $studyLoad) {
    					$sum += $studyLoad->getLoadByType($term->getId());
    				}
    			}
    			$row[1] = $sum;
    			 
    			$result->add($term->getId(), $row);
    		}
    	}
    	return $result;
    }
    
    /**
     * Заголовки для общей суммы по типам нагрузки
     * 
     * @return array
     */
    public static function getStudyWorksTotalTitles() {
    	$result = array();
    	foreach (CTaxonomyManager::getLegacyTaxonomy(TABLE_WORKLOAD_WORK_TYPES)->getTerms()->getItems() as $term) {
    		if ($term->is_total) {
    			$result[$term->getId()] = $term->getValue();
    		}
    	}
    	return $result;
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
	    	->condition("person.id NOT IN (SELECT person_id from ".TABLE_WORKLOAD." WHERE year_id='".$selectedYear."')")
	    	->order("person.fio_short asc");
    	
    	$set = new CRecordSet(false);
    	$set->setQuery($query);
    	foreach ($set->getItems() as $item) {
    		$person = new CPerson($item);
    		if ($person->hasPersonType(TYPE_PPS)) {
    			$personsWithoutLoad->add($person->getId(), $person);
    		}
    	}
    	
    	return $personsWithoutLoad;
    }
    
    /**
     * Лист нагрузок преподавателя по году и семестру
     *
     * @param CArrayList $loads - лист нагрузок
     * @param CYearPart $part - семестр
     *
     * @return CArrayList
     */
    public static function getStudyLoadsByPart($loads, CYearPart $part) {
    	$result = new CArrayList();
    	foreach ($loads as $study) {
    		if ($study->year_part_id == $part->getId()) {
    			$result->add($study->getId(), $study);
    		}
    	}
    	// сортируем нагрузки по названию дисциплин
    	$comparator = new CCorriculumDisciplinesComparator();
    	$sorted = CCollectionUtils::sort($result, $comparator);
    	return $sorted;
    }
    
    /**
     * Копировать выбранные нагрузки
     * 
     * @param int $choice - способ копирования (0 - копирование с перемещением, 1 - только копирование)
     * @param int $lecturerId - id преподавателя, которому копируем нагрузку
     * @param int $yearId - id года, в который копируем
     * @param int $partId - id семестра, в который копируем
     * @param array $loadsToCopy - массив из id нагрузок, выбранных для копирования 
     */
    public static function copySelectedLoads($choice, $lecturerId, $yearId, $partId, $loadsToCopy) {
    	foreach ($loadsToCopy as $loadId) {
    		$studyLoad = CStudyLoadService::getStudyLoad($loadId);
    	
    		if ($choice == 0) {
    			// копирование с перемещением
    			$newLoad = $studyLoad->copy();
    			$newLoad->person_id = $lecturerId;
    			$newLoad->year_id = $yearId;
    			$newLoad->year_part_id = $partId;
    			$newLoad->comment = $newLoad->comment." копия от ".
    						$studyLoad->lecturer->getNameShort().", ".
    						CTaxonomyManager::getYear($yearId)->getValue().", ".
    						CTaxonomyManager::getYearPart($partId)->getValue();
    			$newLoad->save();
    			/**
    			 * Копируем значения по видам работ нагрузки
    			 * @var CStudyLoadWork $work
    			 */
    			foreach ($studyLoad->works->getItems() as $work) {
    				$newWork = $work->copy();
    				$newWork->workload_id = $newLoad->getId();
    				$newWork->save();
    			}
    			/**
    			 * Копируем значения для учебных групп
    			 * @var CStudyLoadGroup $group
    			 */
    			foreach ($studyLoad->groups->getItems() as $group) {
    				$newGroup = $group->copy();
    				$newGroup->workload_id = $newLoad->getId();
    				$newGroup->save();
    			}
    	
    			// удаляем оригинал нагрузки
    			CStudyLoadService::deleteStudyLoad($studyLoad);
    	
    		} elseif ($choice == 1) {
    			// только копирование
    			$newLoad = $studyLoad->copy();
    			$newLoad->person_id = $lecturerId;
    			$newLoad->year_id = $yearId;
    			$newLoad->year_part_id = $partId;
    			$newLoad->comment = $newLoad->comment." копия от ".
    						$studyLoad->lecturer->getNameShort().", ".
    						CTaxonomyManager::getYear($yearId)->getValue().", ".
    						CTaxonomyManager::getYearPart($partId)->getValue();
    			$newLoad->save();
    			/**
    			 * Копируем значения по видам работ нагрузки
    			 * @var CStudyLoadWork $work
    			 */
    			foreach ($studyLoad->works->getItems() as $work) {
    				$newWork = $work->copy();
    				$newWork->workload_id = $newLoad->getId();
    				$newWork->save();
    			}
    			/**
    			 * Копируем значения для учебных групп
    			 * @var CStudyLoadGroup $group
    			 */
    			foreach ($studyLoad->groups->getItems() as $group) {
    				$newGroup = $group->copy();
    				$newGroup->workload_id = $newLoad->getId();
    				$newGroup->save();
    			}
    		}
    	}
    }
    
    /**
     * Тип нагрузки (лекция, практика и др.) из справочника учебных работ по псевдониму
     * 
     * @param string $alias
     * @return CStudyLoadWork
     */
    public static function getWorktypeByAlias($alias) {
    	$works = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD_WORK_TYPES, "name_hours_kind = '".$alias."'")->getItems() as $item) {
    		$work = new CStudyLoadWork($item);
    		$works->add($work->getId(), $work);
    	}
    	return $works->getFirstItem();
    }
    
    /**
     * Вид нагрузки (основная, дополнительная, надбавка, почасовка) по псевдониму
     *
     * @param string $alias
     * @return CStudyLoadType
     */
    public static function getStudyLoadTypeByAlias($alias) {
    	$types = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_PLANNED_TYPES, "comment = '".$alias."'")->getItems() as $item) {
    		$type = new CStudyLoadType($item);
    		$types->add($type->getId(), $type);
    	}
    	return $types->getFirstItem();
    }
    
    /**
     * Учебный семестр по псевдониму
     *
     * @param string $alias
     * @return CYearPart
     */
    public static function getYearPartByAlias($alias) {
    	$parts = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_YEAR_PARTS, "comment = '".$alias."'")->getItems() as $item) {
    		$part = new CYearPart($item);
    		$parts->add($part->getId(), $part);
    	}
    	return $parts->getFirstItem();
    }
    
    /**
     * Сумма часов по итоговой нагрузке преподавателя в указанном году/семестре по типу занятий (для сверки с расписанием)
     *
     * @param CPerson $lecturer - преподаватель
     * @param CTerm $year - учебный год
     * @param CYearPart $part - семестр
     * @param array $kindTypes - массив с типами занятий (лекция, практика, лаб. раб.)
     *
     * @return int
     */
    public static function getSumTotalHoursByKindTypes(CPerson $lecturer, CTerm $year, CYearPart $part, $kindTypes) {
    	$sum = 0;
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD, "person_id = ".$lecturer->getId()." AND year_id = ".$year->getId()." AND year_part_id = ".$part->getId()." AND _is_last_version = 1")->getItems() as $item) {
    		$study = new CStudyLoad($item);
    		foreach ($study->works as $work) {
    			foreach ($kindTypes as $kindType) {
    				$sum += $work->getSumWorkHoursByType($kindType);
    			}
    		}
    	}
    	return $sum;
    }
    /**
     * Сумма часов в расписании у преподавателя в указанном году/семестре по типу занятий: л, пр, л/р
     *
     * @param CPerson $lecturer - преподаватель
     * @param CTerm $year - учебный год
     * @param CYearPart $part - семестр
     *
     * @return int
     */
    public static function getSumHoursInSchedule(CPerson $lecturer, CTerm $year, CYearPart $part) {
    	$schedules = new CArrayList();
    	if (!is_null($lecturer->getUser())) {
    		$schedules = CScheduleService::getScheduleUserByYearAndPart($lecturer->getUser(), $year, $part);
    	}
    	$sum = 0;
    	foreach ($schedules as $schedule) {
    		$item = 0;
    		//формируем массив недель
    		$curArray = CStudyLoadService::curWeekInTimeWeeks($schedule->length);
    		//считаем число часов (для л/р *4, для остальных *2)
    		if ($schedule->kindWork->getAlias() == CScheduleKindWorkConstants::LAB_WORK) {
    			$item = count($curArray)*4;
    		} elseif ($schedule->kindWork->getAlias() == CScheduleKindWorkConstants::LECTURE) {
    			$item = count($curArray)*2;
    		} elseif ($schedule->kindWork->getAlias() == CScheduleKindWorkConstants::PRACTICE) {
    			$item = count($curArray)*2;
    		}
    		$sum += $item;
    	}
    	return $sum;
    }
    /**
     * Сумма часов в расписании у преподавателя в указанном году/семестре по указанному типу занятий
     *
     * @param CPerson $lecturer - преподаватель
     * @param CTerm $year - учебный год
     * @param CYearPart $part - семестр
     * @param int $typeId - тип занятия в нагрузке
     *
     * @return int
     */
    public static function getSumHoursInScheduleByKindTypes(CPerson $lecturer, CTerm $year, CYearPart $part, $typeId) {
    	// сопоставим тип занятия в нагрузке и тип занятия в расписании
    	$typeKindSchedule = "";
    	$typeAliasLoadWork = CBaseManager::getStudyLoadWorkType($typeId)->name_hours_kind;
    	if ($typeAliasLoadWork == CStudyLoadWorkTypeConstants::LABOR_LAB_WORK) {
    		$typeKindSchedule = CScheduleKindWorkConstants::LAB_WORK;
    	} elseif ($typeAliasLoadWork == CStudyLoadWorkTypeConstants::LABOR_LECTURE) {
    		$typeKindSchedule = CScheduleKindWorkConstants::LECTURE;
    	} elseif ($typeAliasLoadWork == CStudyLoadWorkTypeConstants::LABOR_PRACTICE) {
    		$typeKindSchedule = CScheduleKindWorkConstants::PRACTICE;
    	} elseif ($typeAliasLoadWork == CStudyLoadWorkTypeConstants::LABOR_COURSE_PROJECT) {
    		$typeKindSchedule = CScheduleKindWorkConstants::PROJECT;
    	} elseif ($typeAliasLoadWork == CStudyLoadWorkTypeConstants::LABOR_EXAMEN) {
    		$typeKindSchedule = CScheduleKindWorkConstants::EXAMEN;
    	} elseif ($typeAliasLoadWork == CStudyLoadWorkTypeConstants::LABOR_CREDIT) {
    		$typeKindSchedule = CScheduleKindWorkConstants::CREDIT;
    	} elseif ($typeAliasLoadWork == CStudyLoadWorkTypeConstants::LABOR_CONSULTATION) {
    		$typeKindSchedule = CScheduleKindWorkConstants::CONSULTATION;
    	} elseif ($typeAliasLoadWork == CStudyLoadWorkTypeConstants::LABOR_STUDY_PRACTICE) {
    		$typeKindSchedule = CScheduleKindWorkConstants::LECTURE_AND_PRACTICE;
    	} elseif ($typeAliasLoadWork == CStudyLoadWorkTypeConstants::LABOR_WORK_PRACTICE) {
    		$typeKindSchedule = CScheduleKindWorkConstants::LAB_WORK_AND_PRACTICE;
    	}
    	
    	$schedules = new CArrayList();
    	if (!is_null($lecturer->getUser())) {
    		$schedules = CScheduleService::getScheduleUserByYearAndPart($lecturer->getUser(), $year, $part);
    	}
    	$sum = 0;
    	foreach ($schedules as $schedule) {
    		$item = 0;
    		//формируем массив недель
    		$curArray = CStudyLoadService::curWeekInTimeWeeks($schedule->length);
    		//считаем число часов (для л/р *4, для остальных *2)
    		if ($schedule->kindWork->getAlias() == $typeKindSchedule and $typeKindSchedule == CScheduleKindWorkConstants::LAB_WORK) {
    			$item = count($curArray)*4;
    		} elseif ($schedule->kindWork->getAlias() == $typeKindSchedule) {
    			$item = count($curArray)*2;
    		}
    		$sum += $item;
    	}
    	return $sum;
    }
    /**
     * Массив номеров недель
     * 
     * @param string $strNedeli
     * @return array $str_arr
     */
    public static function curWeekInTimeWeeks($strNedeli) {
    	//временный массив
    	$str_tmp_arr = array();	
    	//конечный массив номеров недель
    	$str_arr = array();
    	
    	//удалили пробелы
    	$strNedeli = str_replace(' ','', $strNedeli);
    	//получили разбивку по запятым
    	$str_tmp_arr = split(',',$strNedeli);
    
    	$k = 0; 
    	$findId = 0;
    	for ($i=0; $i < count($str_tmp_arr); $i++){
    		//т.е. элемент включает тире (-)
    		$findId = strpos($str_tmp_arr[$i], '-');
    		if ($findId >= 1) {
    			$valMin = substr($str_tmp_arr[$i], 0, $findId);
    			$valMax = substr($str_tmp_arr[$i], $findId+1);
    			for ($j = $valMin; $j <= $valMax; $j++) {
    				$str_arr[$k] = $j;
    				$k++;
    			}
    		} else {
    			$str_arr[$k] = $str_tmp_arr[$i];
    			$k++;
    		}
    	}
    	return $str_arr;
    }
    /**
     * Количество студентов из нагрузки по дисциплине и семестру
     * 
     * @param CStudyLoad $studyLoad - учебная нагрузка
     * @param CTerm $discipline - дисциплина
     * @param CYearPart $part - учебный семестр
     * @param $kind - вид работы - бюджет/контракт
     * @return int
     */
    public static function getStudentsCountFromLoadByDisciplineAndPart(CStudyLoad $studyLoad, CTerm $discipline, CYearPart $part, $kind) {
    	$studentsCount = 0;
    	if ($studyLoad->discipline_id == $discipline->getId() and $studyLoad->year_part_id == $part->getId()) {
    		if ($kind == CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::BUDGET)->getId()) {
    			$studentsCount += $studyLoad->students_count;
    		} else {
    			$studentsCount += $studyLoad->students_contract_count;
    		}
    	}
    	return $studentsCount;
    }
    
    /**
     * Сотрудники с нагрузкой в указанном году для статистики
     *
     * @param CTerm $year - выбранный учебный год
     * @return CArrayList
     */
    public static function getPersonsWithLoadByYearForStatistic(CTerm $year) {
    	$personsWithLoad = new CArrayList();
    	$query = new CQuery();
    	$query->select("kadri.id as kadri_id,
						dolgnost.name_short as dolgnost,
						dolgnost.id as dolgnostId");
    	$query->from(TABLE_PERSON." as kadri");
    	$query->leftJoin(TABLE_WORKLOAD." as loads", "loads.person_id = kadri.id");
    	$query->leftJoin(TABLE_POSTS." as dolgnost", "dolgnost.id = kadri.dolgnost");
    	$query->condition("loads.year_id = ".$year->getId()." AND loads._is_last_version = 1");
    	$query->group("kadri.id");
    	$query->order("kadri.fio asc");
    	foreach ($query->execute()->getItems() as $personLoad) {
    		$person = CStaffManager::getPerson($personLoad['kadri_id']);
    		$row = new CStudyLoadReportRow();
    		$row->personId = $person->getId();
    		$row->personPost = $personLoad['dolgnost'];
    		$row->personPostId = $personLoad['dolgnostId'];
    		$row->rateSum = $person->getOrdersRate();
    		
    		$hoursSum = 0;
    		$queryHours = new CQuery();
    		$queryHours->select("hours.workload as workload");
    		$queryHours->from(TABLE_WORKLOAD." as loads");
    		$queryHours->innerJoin(TABLE_WORKLOAD_WORKS." as hours", "hours.workload_id = loads.id");
    		$queryHours->condition("loads.year_id = ".$year->getId()." AND loads.person_id = ".$person->getId()." AND loads._is_last_version = 1");
    		foreach ($queryHours->execute()->getItems() as $hours) {
    			$hoursSum += $hours['workload'];
    		}
    		$row->workloadSum = $hoursSum;
    		$personsWithLoad->add($row->personId, $row);
    	}
    	return $personsWithLoad;
    }
    
    /**
     * Установить ограничение редактирования
     * 
     * @param array $loadsToCopy - массив из id нагрузок, выбранных для копирования 
     */
    public static function setEditRestrictionSelectedLoads($selectedLoads) {
    	foreach ($selectedLoads as $loadId) {
    		$studyLoad = CBaseManager::getStudyLoadWithOutVersionControl($loadId);
    		if ($studyLoad->_edit_restriction == 0) {
    			$studyLoad->_edit_restriction = 1;
    			$studyLoad->_created_at = date('Y-m-d G:i:s');
    			$studyLoad->_created_by = CSession::getCurrentPerson()->getId();
    		} else {
    			$studyLoad->_edit_restriction = 0;
    			$studyLoad->_created_at = date('Y-m-d G:i:s');
    			$studyLoad->_created_by = CSession::getCurrentPerson()->getId();
    		}
    		$studyLoad->save();
    	}
    }
}