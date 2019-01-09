<?php

class CIndPlanPersonsReportTableTotal extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Отчет по индивидуальному плану за указанное время по указанным людям. Итог";
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
        $months = $bean->getItem("months");
        $result = array();
        /**
         * @var $plan CIndPlanPersonLoad
         */
        foreach ($targetPlans->getItems() as $plan) {
            $row = array();
            $row[0] = "";
            // запланировано на год
            if (!array_key_exists(1, $row)) {
                $row[1] = 0;
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
                    $preparedData[] = $preparedRow;
                } else {
                    // нет разделения на бюджет-контракт, копируем
                    $preparedData[] = $r;
                }
            }
            $plannedForYear = 0;
            foreach ($preparedData as $preparedRow) {
                // план на весенний семестр
                $plannedForYear += $preparedRow[1];
                // план на осенний семестр
                $plannedForYear += $preparedRow[8];
            }
            $row[1] = $plannedForYear;
            $rows = array(
            	/**
            	 * Столбцы для изменений
            	 *
            	 * Номер слева соответствует номеру столбца в шаблоне, начиная с 0
            	 * Номер справа соответствует порядковому номеру нагрузки из справочника, начиная с 0
            	 * -1 означает, что столбец будет пропущен
            	 */
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
                17 => 13, //занятия с аспирантами
            	18 => 12, //асп
                19 => 17 //НИР
            );
            foreach ($rows as $target=>$source) {
                if (!array_key_exists($target, $row)) {
                    $row[$target] = 0;
                }
                if ($source != -1) {
                	foreach ($months as $month) {
                		$row[$target] += $preparedData[$source][$month];
                	}
                }
                if ($row[$target] == 0) {
                	$row[$target] = "";
                }
            }
            if (!array_key_exists(20, $row)) {
                $row[20] = 0;
            }
            for ($i = 2; $i <= 19; $i++) {
                $row[20] += $row[$i];
            }
            $result[] = $row;
        }
        $sum = array();
    	$sum[0] = "Итого";
    	for ($i = 1; $i <= 20; $i++) {
    		if (!array_key_exists($i, $sum)) {
    			$sum[$i] = 0;
    		}
    		foreach($result as $item) {
    			$sum[$i] += $item[$i];
    		}
    	}
    	$value = array($sum);
        return $value;
    }

} 
