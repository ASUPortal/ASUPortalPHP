<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 05.08.13
 * Time: 20:57
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanPersonLoadTeaching extends CFormModel {
    public $year_id;
    public $kadri_id;

    protected $_fact = null;

    /**
     * Плана по указанному типу в указанном семестре
     * для текущего человека и года
     *
     * @param $type
     * @param $part
     * @return CArrayList
     */
    private function getPlan($type, $part) {
        $byType = new CArrayList();
        $workTypes = array();
        foreach (CActiveRecordProvider::getAllFromTable("spravochnik_uch_rab")->getItems() as $ar) {
            $workTypes[$ar->getItemValue("id")] = $ar->getItemValue("name_hours_kind");
        }
        foreach ($workTypes as $key=>$value) {
            $query = new CQuery();
            $query->select("sum(".$value.") as ".$value.", sum(".$value."_add) as ".$value."_add")
                ->from("hours_kind")
                ->condition("kadri_id=".$this->kadri_id." AND ".
                    "year_id=".$this->year_id." AND ".
                    "hours_kind_type=".$type." AND ".
                    "part_id=".$part);
            $data = $query->execute();
            $data = $data->getFirstItem();
            $summ = $data[$value] + $data[$value."_add"];
            $byType->add($key, $summ);
        }
        return $byType;
    }

    /**
     * @return CArrayList|null
     */
    public function getFact() {
        if (is_null($this->_fact)) {
            $this->_fact = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_LOAD_TEACHING_FACT,
                "id_kadri=".$this->kadri_id." AND id_year=".$this->year_id)->getItems() as $ar) {
                $load = new CIndPlanPersonLoadTeachingFact($ar);
                $this->_fact->add($load->getId(), $load);
            }
        }
        return $this->_fact;
    }

    public function getTableData($type) {
        if ($type == "main") {
            $type = 1;
        } elseif ($type == "add") {
            $type = 2;
        }

        $taxonomy = CTaxonomyManager::getLegacyTaxonomy("spravochnik_uch_rab");
        $workTypes = $taxonomy->getTermsList();
        $result = array();
        /**
         * Название строк
         */
        foreach ($workTypes as $key=>$value) {
            $row[0] = $value;
            $result[$key] = $row;
        }
        /**
         * План на осенний семестр и на весенний тоже сразу
         */
        foreach ($this->getPlan($type, 1)->getItems() as $key=>$value) {
            $row = $result[$key];
            $row[1] = $value;
            $sum = 0;
            if (array_key_exists(16, $row)) {
                $row[16] = $sum;
            }
            $sum += $value;
            $row[16] = $sum;
            $result[$key] = $row;
        }
        foreach ($this->getPlan($type, 2)->getItems() as $key=>$value) {
            $row = $result[$key];
            $row[8] = $value;
            $sum =$row[16];
            $sum += $value;
            $row[16] = $sum;
            $result[$key] = $row;
        }
        /**
         * По месяцам (кроме августа)
         */
        $colByMonth = array(
            1 => 6,
            2 => 9,
            3 => 10,
            4 => 11,
            5 => 12,
            6 => 13,
            7 => 14,
            9 => 2,
            10 => 3,
            11 => 4,
            12 => 5
        );
        for ($month = 1; $month <= 12; $month++) {
            if (array_key_exists($month, $colByMonth)) {
                foreach ($this->getFactByMonth($month, $type)->getItems() as $key=>$value) {
                    $row = $result[$key];
                    /**
                     * По месяцу
                     */
                    $row[$colByMonth[$month]] = $value;
                    /**
                     * За год всего
                     */
                    $val = 0;
                    if (array_key_exists(17, $row)) {
                        $val = $row[17];
                    }
                    $val += $value;
                    $row[17] = $val;
                    /**
                     * По семестрам
                     */
                    if (in_array($month, array(
                        9, 10, 11, 12, 1
                    ))) {
                        // осенний
                        $colId = 7;
                    } else {
                        // весенний
                        $colId = 15;
                    }
                    $sumByPart = 0;
                    if (array_key_exists($colId, $row)) {
                        $sumByPart = $row[$colId];
                    }
                    $sumByPart += $value;
                    $row[$colId] = $sumByPart;
                    $result[$key] = $row;
                }
            }
        }
        /**
         * Суммы по столбцам
         */
        $summRow = array(
            "Итого"
        );
        foreach ($result as $key=>$row) {
            for ($i = 1; $i < count($row); $i++) {
                $summ = 0;
                if (array_key_exists($i, $summRow)) {
                    $summ = $summRow[$i];
                }
                $summ += $row[$i];
                $summRow[$i] = $summ;
            }
        }
        $result[] = $summRow;
        /**
         * Сортировка
         */
        foreach ($result as $key=>$row) {
            ksort($row);
            $result[$key] = $row;
        }
        return $result;
    }

    /**
     * @param $month
     * @return CArrayList
     */
    private function getFactByMonth($month, $type) {
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy("spravochnik_uch_rab");
        $workTypes = $taxonomy->getTermsList();

        $result = new CArrayList();

        foreach ($workTypes as $key=>$value) {
            $result->add($key, 0);
        }
        foreach ($workTypes as $key=>$value) {
            foreach ($this->getFact()->getItems() as $fact) {
                if ($fact->id_month == $month && $fact->hours_kind_type == $type) {
                    $value = $result->getItem($key);
                    $workId = "rab_".$key;
                    $value += $fact->$workId;
                    $result->add($key, $value);
                }
            }
        }

        return $result;
    }
}