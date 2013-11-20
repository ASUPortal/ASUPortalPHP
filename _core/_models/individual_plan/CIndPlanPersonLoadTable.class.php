<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 05.11.13
 * Time: 19:07
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanPersonLoadTable extends CFormModel{
    private $_load = null;
    private $_workTypes = null;
    private $_workTypesAlias = null;
    public $work_type = 1;
    public $load_id;

    /**
     * @param CIndPlanPersonLoad $load
     */
    function __construct(CIndPlanPersonLoad $load) {
        $this->_load = $load;
        $this->load_id = $load->getId();
    }

    /**
     * @return array
     */
    private function getWorktypes() {
        if (is_null($this->_workTypes)) {
            $this->_workTypes = array();
            foreach (CTaxonomyManager::getLegacyTaxonomy("spravochnik_uch_rab")->getTerms()->getItems() as $term) {
                $this->_workTypes[$term->getId()] = $term->getValue();
            }
        }
        return $this->_workTypes;
    }

    /**
     * @return array
     */
    private function getWorktypesAlias() {
        if (is_null($this->_workTypesAlias)) {
            $this->_workTypesAlias = array();
            foreach (CTaxonomyManager::getLegacyTaxonomy("spravochnik_uch_rab")->getTerms()->getItems() as $term) {
                $this->_workTypesAlias[$term->getId()] = $term->name_hours_kind;
            }
        }
        return $this->_workTypesAlias;
    }

    /**
     * @return CIndPlanPersonLoad|null
     */
    public function getLoad() {
        return $this->_load;
    }

    /**
     * @return array
     */
    public function getTable($showTotals = false) {
        $result = array();
        /**
         * Этот код специально написан так тупо чтобы быть прозрачным
         * и очевидным. Иначе приходится слишком много думать, чтобы
         * поправить простую ошибку
         *
         * Если есть разделение на бюджет и контракт, то
         * колонок будет в два раза больше
         */
        if ($this->getLoad()->isSeparateContract()) {
            foreach ($this->getWorktypes() as $key=>$type) {
                $row = array();
                // тип работы
                $row[0] = $type;
                // план на семестр (по бюджету и контракту)
                $row[1] = $this->getLoadByMonthAndType(20, $key, 0);
                $row[2] = $this->getLoadByMonthAndType(20, $key, 1);
                // данные на осенний семестр (месяцы с 9 по 12 и 1)
                $row[3] = $this->getLoadByMonthAndType(9, $key, 0);
                $row[4] = $this->getLoadByMonthAndType(9, $key, 1);
                $row[5] = $this->getLoadByMonthAndType(10, $key, 0);
                $row[6] = $this->getLoadByMonthAndType(10, $key, 1);
                $row[7] = $this->getLoadByMonthAndType(11, $key, 0);
                $row[8] = $this->getLoadByMonthAndType(11, $key, 1);
                $row[9] = $this->getLoadByMonthAndType(12, $key, 0);
                $row[10] = $this->getLoadByMonthAndType(12, $key, 1);
                $row[11] = $this->getLoadByMonthAndType(1, $key, 0);
                $row[12] = $this->getLoadByMonthAndType(1, $key, 1);
                // итого за осенний семестр (по бюджету и контракту)
                $row[13] = $row[3] + $row[5] + $row[7] + $row[9] + $row[11];
                $row[14] = $row[4] + $row[6] + $row[8] + $row[10] + $row[12];
                // план на весенний семестр (месяцы с 2 по 7)
                $row[15] = $this->getLoadByMonthAndType(21, $key, 0);
                $row[16] = $this->getLoadByMonthAndType(21, $key, 1);
                // данные на весенний семестр (месяцы с 2 по 7)
                $row[17] = $this->getLoadByMonthAndType(2, $key, 0);
                $row[18] = $this->getLoadByMonthAndType(2, $key, 1);
                $row[19] = $this->getLoadByMonthAndType(3, $key, 0);
                $row[20] = $this->getLoadByMonthAndType(3, $key, 1);
                $row[21] = $this->getLoadByMonthAndType(4, $key, 0);
                $row[22] = $this->getLoadByMonthAndType(4, $key, 1);
                $row[23] = $this->getLoadByMonthAndType(5, $key, 0);
                $row[24] = $this->getLoadByMonthAndType(5, $key, 1);
                $row[25] = $this->getLoadByMonthAndType(6, $key, 0);
                $row[26] = $this->getLoadByMonthAndType(6, $key, 1);
                $row[27] = $this->getLoadByMonthAndType(7, $key, 0);
                $row[28] = $this->getLoadByMonthAndType(7, $key, 1);
                // итого за весенний семестр (по бюджету и контракту)
                $row[29] = $row[17] + $row[19] + $row[21] + $row[23] + $row[25] + $row[27];
                $row[30] = $row[18] + $row[20] + $row[22] + $row[24] + $row[26] + $row[28];
                // по плану (сумма планов за два семестра)
                $row[31] = $row[1] + $row[15];
                $row[32] = $row[2] + $row[16];
                // выполнено (сумма итого)
                $row[33] = $row[13] + $row[29];
                $row[34] = $row[14] + $row[30];
                // добавляем в таблицу
                $result[$key] = $row;
            }
            if ($showTotals) {
                // сумма
                $row = array();
                $row[0] = "Итого";
                foreach ($result as $dataRow) {
                    for ($i = 1; $i < 35; $i++) {
                        $row[$i] += $dataRow[$i];
                    }
                }
                $result[] = $row;                 
            }
        } else {
            foreach ($this->getWorktypes() as $key=>$type) {
                $row = array();
                // тип работы
                $row[0] = $type;
                // план на семестр
                $row[1] = $this->getLoadByMonthAndType(20, $key);
                // данные на осенний семестр (месяцы с 9 по 12 и 1)
                $row[2] = $this->getLoadByMonthAndType(9, $key);
                $row[3] = $this->getLoadByMonthAndType(10, $key);
                $row[4] = $this->getLoadByMonthAndType(11, $key);
                $row[5] = $this->getLoadByMonthAndType(12, $key);
                $row[6] = $this->getLoadByMonthAndType(1, $key);
                // итого за осенний семестр
                $row[7] = $row[2] + $row[3] + $row[4] + $row[5] + $row[6];
                // план на весенний семестр (месяцы с 2 по 7)
                $row[8] = $this->getLoadByMonthAndType(21, $key);
                // данные на весенний семестр (месяцы с 2 по 7)
                $row[9] = $this->getLoadByMonthAndType(2, $key);
                $row[10] = $this->getLoadByMonthAndType(3, $key);
                $row[11] = $this->getLoadByMonthAndType(4, $key);
                $row[12] = $this->getLoadByMonthAndType(5, $key);
                $row[13] = $this->getLoadByMonthAndType(6, $key);
                $row[14] = $this->getLoadByMonthAndType(7, $key);
                // итого за весенний семестр
                $row[15] = $row[9] + $row[10] + $row[11] + $row[12] + $row[13] + $row[14];
                // по плану (сумма планов за два семестра)
                $row[16] = $row[1] + $row[8];
                // выполнено (сумма итого)
                $row[17] = $row[7] + $row[15];
                // добавляем в таблицу
                $result[$key] = $row;
            }
            if ($showTotals) {
                // сумма
                $row = array();
                $row[0] = "Итого";
                foreach ($result as $dataRow) {
                    for ($i = 1; $i < 18; $i++) {
                        $row[$i] += $dataRow[$i];
                    }
                }
                $result[] = $row;                 
            }
        }
        return $result;
    }

    /**
     * Нагрузка по типу (лекция, практика, ргр)
     * Месяцу (1 - январь, 2 - февраль...)
     * Типу данных
     *      0 - только бюджет
     *      1 - только контракт
     *      2 - сумма бюджета и контракта
     * @param $month
     * @param $type
     * @param int $dataType
     * @return int
     */
    private function getLoadByMonthAndType($month, $type, $dataType = 2) {
        $result = 0;
        if ($dataType == 2) {
            foreach ($this->getLoad()->getWorksByType(1)->getItems() as $work) {
                if ($work->load_month_id == $month &&
                    $work->load_type_id == $type) {

                    $result += $work->load_value;
                }
            }
        } else {
            foreach ($this->getLoad()->getWorksByType(1)->getItems() as $work) {
                if ($work->load_month_id == $month &&
                    $work->load_type_id == $type &&
                    $work->load_is_contract == $dataType) {

                    $result += $work->load_value;
                }
            }
        }
        return $result;
    }

    /**
     * Нагрузка по типу (лекция, практика, т.п.)
     * Параметрам
     *      type_1 - основная
     *      type_2 - дополнительная
     *      type_3 - надбавка
     *      type_4 - почасовка
     *      filials - с учетом выезда
     * Семестру
     *      1 - осенний
     *      2 - весенний
     * Типу данных
     *      0 - только бюджет
     *      1 - только контракт
     *      2 - сумма бюджета и контракта
     *
     * @param $typeAlias
     * @param array $params
     * @param int $period
     * @param int $dataType
     * @return int
     */
    private function getLoadPlanByType($typeAlias, $params = array(), $period = 1, $dataType = 2) {
        $result = 0;
        $defaulParams = array(
            "type_1" => false,
            "type_2" => false,
            "type_3" => false,
            "type_4" => false,
            "filials" => false
        );
        $params = array_merge($defaulParams, $params);
        // общие условия
        $condition = array(
            "kadri_id = ".$this->getLoad()->person_id,
            "year_id = ".$this->getLoad()->year->getId(),
            "part_id = ".$period
        );
        // типы нагрузки
        $types = array();
        if ($params["type_1"]) {
            $types[] = "1";
        }
        if ($params["type_2"]) {
            $types[] = "2";
        }
        if ($params["type_3"]) {
            $types[] = "3";
        }
        if ($params["type_4"]) {
            $types[] = "4";
        }
        if (count($types) > 0) {
            $condition[] = "hours_kind_type in (".implode(", ", $types).")";
        } else {
            $condition[] = "hours_kind_type in (0)";
        }
        if ($params["filials"]) {
            $condition[] = "on_filial in (0, 1)";
        } else {
            $condition[] = "on_filial in (0)";
        }
        // какие столбцы брать и считать ли сумму
        $query = new CQuery();
        if ($dataType == 2) {
            $query->select("IFNULL(SUM(".$typeAlias."), 0) + IFNULL(SUM(".$typeAlias."_add), 0) as value");
        } elseif ($dataType == 1) {
            $query->select("IFNULL(SUM(".$typeAlias."_add), 0) as value");
        } elseif ($dataType == 0) {
            $query->select("IFNULL(SUM(".$typeAlias."), 0) as value");
        }
        $query->from(TABLE_IND_PLAN_PLANNED);
        $query->condition(implode(" AND ", $condition));
        $data = $query->execute()->getFirstItem();
        $result = $data["value"];
        return $result;
    }
    public function getFieldName($work_id, $column_id, $isContract = 0) {
        $months = array();
        // связь между номерами колонок и месяцами
        if ($this->getLoad()->isSeparateContract()) {
            $months = array(
                3 => 9,
                4 => 9,
                5 => 10,
                6 => 10,
                7 => 11,
                8 => 11,
                9 => 12,
                10 => 12,
                11 => 1,
                12 => 1,
                17 => 2,
                18 => 2,
                19 => 3,
                20 => 3,
                21 => 4,
                22 => 4,
                23 => 5,
                24 => 5,
                25 => 6,
                26 => 6,
                27 => 7,
                28 => 7,

                1 => 20,
                2 => 20,
                15 => 21,
                16 => 21
            );
        } else {
            $months = array(
                2 => 9,
                3 => 10,
                4 => 11,
                5 => 12,
                6 => 1,
                9 => 2,
                10 => 3,
                11 => 4,
                12 => 5,
                13 => 6,
                14 => 7,

                1 => 20,
                8 => 21
            );
        }
        return "CModel[data][".$isContract."][".$work_id."][".$months[$column_id]."]";
    }
    public function save() {
        // удаляем старые данные
        foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_WORKS, "load_id=".$this->getLoad()->getId())->getItems() as $ar) {
            $ar->remove();
        }
        // добавляем новые
        foreach ($this->data as $isContract=>$works) {
            foreach ($works as $type_id=>$months) {
                foreach ($months as $month_id=>$value) {
                    $obj = new CIndPlanPersonWork();
                    $obj->load_id = $this->getLoad()->getId();
                    $obj->work_type = "1";
                    $obj->load_month_id = $month_id;
                    $obj->load_type_id = $type_id;
                    $obj->load_is_contract = $isContract;
                    $obj->load_value = $value;
                    $obj->save();
                }
            }
        }
    }
    public function getAutoFillData($type_1 = false, $type_2 = false, $type_3 = false, $type_4 = false, $filias = false) {
        $result = array();
        /**
         * Этот код специально написан так тупо чтобы быть прозрачным
         * и очевидным. Иначе приходится слишком много думать, чтобы
         * поправить простую ошибку
         */
        if ($this->getLoad()->isSeparateContract()) {
            foreach ($this->getWorktypesAlias() as $typeId=>$typeAlias) {
                $dataRow = array(
                    "20" => $this->getLoadPlanByType($typeAlias, array(
                        "type_1" => $type_1,
                        "type_2" => $type_2,
                        "type_3" => $type_3,
                        "type_4" => $type_4,
                        "filials" => $filias
                    ), 1, 0),
                    "21" => $this->getLoadPlanByType($typeAlias, array(
                        "type_1" => $type_1,
                        "type_2" => $type_2,
                        "type_3" => $type_3,
                        "type_4" => $type_4,
                        "filials" => $filias
                    ), 2, 0)
                );
                $result[0][$typeId] = $dataRow;
                $dataRow = array(
                    "20" => $this->getLoadPlanByType($typeAlias, array(
                        "type_1" => $type_1,
                        "type_2" => $type_2,
                        "type_3" => $type_3,
                        "type_4" => $type_4,
                        "filials" => $filias
                    ), 1, 1),
                    "21" => $this->getLoadPlanByType($typeAlias, array(
                        "type_1" => $type_1,
                        "type_2" => $type_2,
                        "type_3" => $type_3,
                        "type_4" => $type_4,
                        "filials" => $filias
                    ), 2, 1)
                );
                $result[1][$typeId] = $dataRow;
            }
        } else {
            foreach ($this->getWorktypesAlias() as $typeId=>$typeAlias) {
                $dataRow = array(
                    "20" => $this->getLoadPlanByType($typeAlias, array(
                        "type_1" => $type_1,
                        "type_2" => $type_2,
                        "type_3" => $type_3,
                        "type_4" => $type_4,
                        "filials" => $filias
                    ), 1),
                    "21" => $this->getLoadPlanByType($typeAlias, array(
                        "type_1" => $type_1,
                        "type_2" => $type_2,
                        "type_3" => $type_3,
                        "type_4" => $type_4,
                        "filials" => $filias
                    ), 2)
                );
                $result[0][$typeId] = $dataRow;
            }
        }
        return $result;
    }
}
