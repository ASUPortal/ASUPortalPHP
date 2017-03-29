<?php

class CIndPlanPersonsReportTableTotalByTime extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Отчет по индивидуальному плану за указанное время по указанным людям. Итог. Почасовка";
    }

    public function getFieldDescription()
    {
        return "Данные берутся из CStatefullBean по beanId";
    }

    public function getParentClassField()
    {
        // TODO: Implement getParentClassField() method.
    }

    public function getFieldType()
    {
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
        /**
         * @var $bean CStatefullBean
         */
        $bean = CApp::getApp()->beans->getStatefullBean(CRequest::getString("beanId"));
        $persons = new CArrayList();
        foreach ($bean->getItem("id")->getItems() as $person_id) {
            $person = CStaffManager::getPerson($person_id);
            if (!is_null($person)) {
                $persons->add($person->getId(), $person);
            }
        }
        /**
         * Отфильтруем нужные планы
         */
        $targetPlans = new CArrayList();
        /**
         * @var $person CPerson
         */
        foreach ($persons->getItems() as $person) {
            foreach ($person->getIndPlansByYears($bean->getItem("year_id"))->getItems() as $year_id=>$plans) {
                foreach ($plans->getItems() as $plan) {
                    if (in_array($plan->type, $bean->getItem("types")->getItems())) {
                        $targetPlans->add($plan->getId(), $plan);
                    }
                }
            }
        }
        $month = $bean->getItem("month");
        $month = $month->getFirstItem();
        $result = array();
        /**
         * @var $plan CIndPlanPersonLoad
         */
        foreach ($targetPlans->getItems() as $plan) {
            $row = array();
            $row[0] = "";
            // запланировано на год бюджет
            if (!array_key_exists(1, $row)) {
                $row[1] = 0;
            }
            // запланировано на год контракт
            if (!array_key_exists(2, $row)) {
            	$row[2] = 0;
            }
            // итого бюджет
            if (!array_key_exists(20, $row)) {
            	$row[20] = 0;
            }
            // итого контракт
            if (!array_key_exists(21, $row)) {
            	$row[21] = 0;
            }
            $preparedData = array();
            $table = $plan->getStudyLoadTable()->getTable();
            foreach ($table as $r) {
                if ($plan->isSeparateContract()) {
                    // если есть бюджет-контракт, то суммируем их
                    $preparedRow = array();
                    $preparedRow[0] = $r[0];
                    for ($i = 1; $i <= 17; $i++) {
                    	$preparedRow[$i] = $r[($i * 2)] + $r[($i * 2 - 1)];
                    }
                    // не разделяем бюджет-контракт плана на осенний семестр
                    $preparedRow[18] = $r[1];
                    $preparedRow[19] = $r[2];
                    // не разделяем бюджет-контракт плана на весенний семестр
                    $preparedRow[20] = $r[15];
                    $preparedRow[21] = $r[16];
                    // не разделяем бюджет-контракт итога за осенний семестр
                    $preparedRow[20] = $r[13];
                    $preparedRow[21] = $r[14];
                    // не разделяем бюджет-контракт итога за весенний семестр
                    $preparedRow[22] = $r[29];
                    $preparedRow[23] = $r[30];
                    $preparedData[] = $preparedRow;
                } else {
                    // нет разделения на бюджет-контракт, копируем
                    $preparedData[] = $r;
                }
            }
            if ($plan->isSeparateContract()) {
            	$plannedForYearBudget = 0;
            	foreach ($preparedData as $preparedRow) {
            		// план на осенний семестр бюджет
            		$plannedForYearBudget += $preparedRow[18];
            		// план на весенний семестр бюджет
            		$plannedForYearBudget += $preparedRow[20];
            	}
            	$row[1] = $plannedForYearBudget;
            	 
            	$plannedForYearContract = 0;
            	foreach ($preparedData as $preparedRow) {
            		// план на осенний семестр контракт
            		$plannedForYearContract += $preparedRow[19];
            		// план на весенний семестр контракт
            		$plannedForYearContract += $preparedRow[21];
            	}
            	$row[2] = $plannedForYearContract;
            	 
            	$resultForYearBudget = 0;
            	foreach ($preparedData as $preparedRow) {
            		// итог на осенний семестр бюджет
            		$resultForYearBudget += $preparedRow[20];
            		// итог на весенний семестр бюджет
            		$resultForYearBudget += $preparedRow[22];
            	}
            	$row[20] = $resultForYearBudget;
            	 
            	$resultForYearContract = 0;
            	foreach ($preparedData as $preparedRow) {
            		// итог на осенний семестр контракт
            		$resultForYearContract += $preparedRow[21];
            		// итог на весенний семестр контракт
            		$resultForYearContract += $preparedRow[23];
            	}
            	$row[21] = $resultForYearContract;
            	
            	$rows = array(
            			3 => 0, //лекц
            			4 => 1, //прак
            			5 => 2, //лаб
            			6 => -1,
            			7 => 4, //кп
            			8 => 8, //коллоквиум
            			9 => 14, //ргр
            			10 => 3, //конс
            			11 => 15, //уч. прак.
            			12 => 7, //произв. прак.
            			13 => 5, //зач
            			14 => 6, //экз
            			15 => 9, //дип. проект.
            			16 => 10, //ГЭК
            			17 => 16, //КСР
            			18 => -1,
            			19 => 12 //асп
            	);
            } else {
            	$plannedForYear = 0;
            	foreach ($preparedData as $preparedRow) {
            		// план на весенний семестр
            		$plannedForYear += $preparedRow[1];
            		// план на осенний семестр
            		$plannedForYear += $preparedRow[8];
            	}
            	$row[1] = $plannedForYear;
            	$rows = array(
            			2 => 0, //лекц
            			3 => 1, //прак
            			4 => 2, //лаб
            			5 => -1,
            			6 => 4, //кп
            			7 => 8, //коллоквиум
            			8 => 14, //ргр
            			9 => 3, //конс
            			10 => 15, //уч. прак.
            			11 => 7, //произв. прак.
            			12 => 5, //зач
            			13 => 6, //экз
            			14 => 9, //дип. проект.
            			15 => 10, //ГЭК
            			16 => 16, //КСР
            			17 => -1,
            			18 => 12 //асп
            	);
            }
            foreach ($rows as $target=>$source) {
                if (!array_key_exists($target, $row)) {
                    $row[$target] = 0;
                }
                if ($source != -1) {
                    $row[$target] += $preparedData[$source][$month];
                }
            }
            if (!$plan->isSeparateContract()) {
            	if (!array_key_exists(22, $row)) {
            		$row[22] = 0;
            	}
            	for ($i = 2; $i <= 18; $i++) {
            		$row[22] += $row[$i];
            	}
            }
            $result[] = $row;
        }
        $sum = array();
    	$sum[0] = "Итого";
    	for ($i = 3; $i <= 19; $i++) {
    		if (!array_key_exists($i, $sum)) {
    			$sum[$i] = 0;
    		}
    		foreach($result as $item) {
    			$sum[$i] += $item[$i];
    		}
    	}
    	$total = array($sum);
        return $total;
    }

} 