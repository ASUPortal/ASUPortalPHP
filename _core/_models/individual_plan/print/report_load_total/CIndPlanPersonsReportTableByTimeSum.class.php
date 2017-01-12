<?php

class CIndPlanPersonsReportTableByTimeSum extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Отчет по индивидуальному плану за указанное время по указанным людям. Почасовка. Только итог";
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
            if (array_key_exists($plan->person_id, $result)) {
                $row = $result[$plan->person_id];
            }
            $row[0] = count($result) + 1;
            $row[1] = "";
            if (!is_null($plan->person)) {
                if (!is_null($plan->person->getPost())) {
                    $row[1] = $plan->person->getPost()->name_short;
                }
            }
            $row[2] = "";
            if (!is_null($plan->person)) {
                if (!is_null($plan->person->degree)) {
                    $row[2] = $plan->person->degree->comment;
                }
            }
            $row[3] = "";
            if (!is_null($plan->person)) {
                $row[3] = $plan->person->fio_short;
            }
            // план на семестр бюджет
            if (!array_key_exists(4, $row)) {
                $row[4] = 0;
            }
            // план на семестр контракт
            if (!array_key_exists(5, $row)) {
            	$row[5] = 0;
            }
            // итого бюджет
            if (!array_key_exists(6, $row)) {
            	$row[6] = 0;
            }
            // итого контракт
            if (!array_key_exists(7, $row)) {
            	$row[7] = 0;
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
            	if (in_array($month, array(
            			2, 3, 4, 5, 6
            	))) {
            		foreach ($preparedData as $preparedRow) {
            			$row[4] += $preparedRow[18];
            			$row[5] += $preparedRow[19];
            			$row[6] += $preparedRow[20];
            			$row[7] += $preparedRow[21];
            		}
            	} else {
            		foreach ($preparedData as $preparedRow) {
            			$row[4] += $preparedRow[20];
            			$row[5] += $preparedRow[21];
            			$row[6] += $preparedRow[22];
            			$row[7] += $preparedRow[23];
            		}
            	}
            	$rows = array(
            			6 => 0, //лекц
            			7 => 1, //прак
            			8 => 2, //лаб
            			9 => -1,
            			10 => 4, //кп
            			11 => -1,
            			12 => 14, //ргр
            			13 => 3, //конс
            			14 => 15, //уч. прак.
            			15 => 7, //произв. прак.
            			16 => 5, //зач
            			17 => 6, //экз
            			18 => 9, //дип. проект.
            			19 => 10, //ГЭК
            			20 => 16, //КСР
            			21 => -1,
            			22 => 12 //асп
            	);
            } else {
            	if (in_array($month, array(
            			2, 3, 4, 5, 6
            	))) {
            		foreach ($preparedData as $preparedRow) {
            			$row[4] += $preparedRow[1];
            		}
            	} else {
            		foreach ($preparedData as $preparedRow) {
            			$row[4] += $preparedRow[8];
            		}
            	}
            	$rows = array(
            			5 => 0, //лекц
            			6 => 1, //прак
            			7 => 2, //лаб
            			8 => -1,
            			9 => 4, //кп
            			10 => -1,
            			11 => 14, //ргр
            			12 => 3, //конс
            			13 => 15, //уч. прак.
            			14 => 7, //произв. прак.
            			15 => 5, //зач
            			16 => 6, //экз
            			17 => 9, //дип. проект.
            			18 => 10, //ГЭК
            			19 => 16, //КСР
            			20 => -1,
            			21 => 12 //асп
            	);
            }
            foreach ($rows as $target=>$source) {
            	if (!array_key_exists($target, $r)) {
    				$r[$target] = 0;
    			}
    			if ($source != -1) {
    				$r[$target] += $preparedData[$source][$month];
    			}
            }
            if (!$plan->isSeparateContract()) {
            	if (!array_key_exists(5, $row)) {
            		$row[5] = 0;
            	}
            	for ($i = 5; $i <= 21; $i++) {
            		$row[5] += $r[$i];
            	}
            }
            $result[$plan->person_id] = $row;
        }
        return $result;
    }

} 