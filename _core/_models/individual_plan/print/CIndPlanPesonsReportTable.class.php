<?php
/**
 * Created by PhpStorm.
 * User: ABarmin
 * Date: 12.02.2015
 * Time: 12:43
 */

class CIndPlanPesonsReportTable extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Отчет по индивидуальному плану за указанное время по указанным людям";
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
            $row[0] = "АСУ";
            $row[1] = count($result) + 1;
            $row[2] = "";
            if (!is_null($plan->person)) {
                if (!is_null($plan->person->title)) {
                    $row[2] = $plan->person->title->getValue();
                }
            }
            $row[3] = "";
            if (!is_null($plan->person)) {
                if (!is_null($plan->person->degree)) {
                    $row[3] = $plan->person->degree->getValue();
                }
            }
            $row[4] = "";
            if (!is_null($plan->person)) {
                $row[4] = $plan->person->fio_short;
            }
            $row[5] = 0; // план на семестр
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
                $row[5] = 0;
                foreach ($preparedData as $preparedRow) {
                    $row[5] += $preparedRow[1];
                }
            } else {
                $row[5] = 0;
                foreach ($preparedData as $preparedRow) {
                    $row[5] += $preparedRow[8];
                }
            }
            $rows = array(
                6 => 0,
                7 => 1,
                8 => 2,
                9 => -1,
                10 => 4,
                11 => -1,
                12 => 14,
                13 => 3,
                14 => -1,
                15 => 7,
                16 => 5,
                17 => 6,
                18 => 9,
                19 => 10,
                20 => 12,
                21 => -1
            );
            foreach ($rows as $target=>$source) {
                $row[$target] = 0;
                if ($source != -1) {
                    $row[$target] = $preparedData[$source][$month];
                }
            }
            $row[22] = 0;
            for ($i = 6; $i <= 21; $i++) {
                $row[22] += $row[$i];
            }
            $result[] = $row;
        }
        return $result;
    }

} 