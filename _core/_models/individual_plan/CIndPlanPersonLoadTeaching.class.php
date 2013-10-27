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
        } elseif ($type == "hours") {
            $type = 4;
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
         * Заполняем все пустыми данными
         */
        foreach ($result as $key=>$value) {
            $row = $value;
            for ($i = 1; $i <= 24; $i++) {
                $row[$i] = 0;
            }
            $result[$key] = $row;
        }

        $colByMonth = $this->getColumnForMonth();
        for ($month = 1; $month <= 12; $month++) {
            if (array_key_exists($month, $colByMonth)) {
                // бюджет
                foreach ($this->getFactByMonth($month, $type, 0)->getItems() as $key=>$value) {
                    $row = $result[$key];
                    $row[$colByMonth[$month]] = $value;
                    $result[$key] = $row;
                }
                // контракт
                foreach ($this->getFactByMonth($month, $type, 1)->getItems() as $key=>$value) {
                    $row = $result[$key];
                    $row[$colByMonth[$month] + 1] = $value;
                    $result[$key] = $row;
                }
            }
        }
        return $result;
    }

    /**
     * @param $month
     * @param $type
     * @param $isContract
     * @return CArrayList
     */
    private function getFactByMonth($month, $type, $isContract) {
        $result = new CArrayList();

        foreach (CTaxonomyManager::getLegacyTaxonomy("spravochnik_uch_rab")->getTerms()->getItems() as $key=>$value) {
            $result->add($key, 0);
            foreach ($this->getFact()->getItems() as $fact) {
                if ($fact->id_month == $month && $fact->hours_kind_type == $type && $fact->is_contract_form == $isContract) {
                    $val = $result->getItem($key);
                    $part = "rab_".$key;
                    $val += $fact->$part;
                    $result->add($key, $val);
                }
            }
        }

        return $result;
    }

    /**
     *
     *
     * @return array
     */
    private function getColumnForMonth() {
        return array(
            1 => 9,
            2 => 11,
            3 => 13,
            4 => 15,
            5 => 17,
            6 => 19,
            7 => 21,
            9 => 1,
            10 => 3,
            11 => 5,
            12 => 7
        );
    }

    /**
     * Название поля для редактирования
     *
     * @param $rowId
     * @param $cellId
     * @param $type
     * @return string
     */
    public function getFieldName($rowId, $cellId, $type) {
        $columns = $this->getColumnForMonth();
        // четные колонки - контракт, нечетные - бюджет
        if ($cellId % 2 == 0) {
            // контракт
            $cellId--;
            $month = array_search($cellId, $columns);
            return self::getClassName()."[".$type."][1][".$month."][".$rowId."]";
        } else {
            // бюджет
            $month = array_search($cellId, $columns);
            return self::getClassName()."[".$type."][0][".$month."][".$rowId."]";
        }
    }

    public function save() {
        /**
         * Удаляем старые данные
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_LOAD_TEACHING_FACT,
                 "id_kadri=".$this->kadri_id." AND ".
                 "id_year=".$this->year_id)->getItems() as $ar) {

            $ar->remove();
        }
        /**
         * Создаем новые
         *
         * Классификация и состав массивов следующий:
         * номер типа (основная, дополнительная, почасовка)
         *    тип оплаты (бюджет - 0, контракт - 1)
         *       месяц (обычный порядок)
         *          вид нагрузки (номер)
         */
        $data = $this->getItems()->getItems();
        for ($type = 1; $type <= 4; $type++) {
            if ($type != 3) {
                if (array_key_exists($type, $data)) {
                    $byType = $data[$type];
                    foreach ($byType as $isContract=>$monthData) {
                        foreach ($monthData as $monthId=>$byKind) {
                            $obj = new CIndPlanPersonLoadTeachingFact();
                            $obj->id_kadri = $this->kadri_id;
                            $obj->id_year = $this->year_id;
                            $obj->hours_kind_type = $type;
                            $obj->id_month = $monthId;
                            $obj->is_contract_form = $isContract;
                            foreach ($byKind as $kind=>$value) {
                                $work = "rab_".$kind;
                                $obj->$work = $value;
                            }
                            $obj->save();
                        }
                    }
                }
            }
        }
    }
}