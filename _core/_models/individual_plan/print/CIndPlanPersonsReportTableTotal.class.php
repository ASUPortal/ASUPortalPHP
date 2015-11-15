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
        $month = $bean->getItem("month");
        $month = $month->getFirstItem();
        $result = array();
        /**
         * @var $plan CIndPlanPersonLoad
         */
        foreach ($targetPlans->getItems() as $plan) {
            $row = array();
            $row[0] = "";
            $row[1] = "";
            $row[2] = "";
            $row[3] = "";
            $row[4] = "";
            // план на семестр
            if (!array_key_exists(5, $row)) {
                $row[5] = 0;
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
            if (in_array($month, array(
                2, 3, 4, 5, 6
            ))) {
                foreach ($preparedData as $preparedRow) {
                    $row[5] += $preparedRow[1];
                }
            } else {
                foreach ($preparedData as $preparedRow) {
                    $row[5] += $preparedRow[8];
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
                14 => 5, //зач
                15 => 6, //экз
                16 => 7, //произв. прак.
                17 => 8, //рец
                18 => 9, //дип. проект.
                19 => 10, //ГЭК
                20 => 15, //уч. прак.
                21 => 16 //КСР
            );
            foreach ($rows as $target=>$source) {
                if (!array_key_exists($target, $row)) {
                    $row[$target] = 0;
                }
                if ($source != -1) {
                    $row[$target] += $preparedData[$source][$month];
                }
            }
            if (!array_key_exists(22, $row)) {
                $row[22] = 0;
            }
            for ($i = 6; $i <= 21; $i++) {
                $row[22] += $row[$i];
            }
            $result[$plan->person_id] = $row;
        }
        $sum5=0;
        $sum6=0;
        $sum7=0;
        $sum8=0;
        $sum9=0;
        $sum10=0;
        $sum11=0;
        $sum12=0;
        $sum13=0;
        $sum14=0;
        $sum15=0;
        $sum16=0;
        $sum17=0;
        $sum18=0;
        $sum19=0;
        $sum20=0;
        $sum21=0;
        $sum22=0;
        foreach($result as $i) {
        	$sum5+=$i[5];
        	$sum6+=$i[6];
        	$sum7+=$i[7];
        	$sum8+=$i[8];
        	$sum9+=$i[9];
        	$sum10+=$i[10];
        	$sum11+=$i[11];
        	$sum12+=$i[12];
        	$sum13+=$i[13];
        	$sum14+=$i[14];
        	$sum15+=$i[15];
        	$sum16+=$i[16];
        	$sum17+=$i[17];
        	$sum18+=$i[18];
        	$sum19+=$i[19];
        	$sum20+=$i[20];
        	$sum21+=$i[21];
        	$sum22+=$i[22];
        }
        $total = array();
        $total[0] = "Итог";
        $total[1] = "";
        $total[2] = "";
        $total[3] = "";
        $total[4] = "";
        $total[5] = $sum5;
        $total[6] = $sum6;
        $total[7] = $sum7;
        $total[8] = $sum8;
        $total[9] = $sum9;
        $total[10] = $sum10;
        $total[11] = $sum11;
        $total[12] = $sum12;
        $total[13] = $sum13;
        $total[14] = $sum14;
        $total[15] = $sum15;
        $total[16] = $sum16;
        $total[17] = $sum17;
        $total[18] = $sum18;
        $total[19] = $sum19;
        $total[20] = $sum20;
        $total[21] = $sum21;
        $total[22] = $sum22;
        $res = array($total);
        return $res;
    }

} 