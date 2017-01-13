<?php
/**
 * Класс для работы с набором данных на одной странице
 *
 */
class COnePageRecordSet extends CRecordSet {
    private $_items = null;
    private $_page = null;
    private $_pageSize = null;
    private $_paginator = null;
    private $_query = null;
    private $_manualAdded = false;
    private $_useGlobalSearch = false;
    private $_isAclControlledSet = false;

    /**
     * Набор данных на одной странице
     *
     * @return CArrayList
     */
    public function getPaginated() {
        if ($this->_manualAdded) {
            $res = new CArrayList();
            foreach ($this->getItems() as $key=>$value) {
            	$res->add($key, $value);
            }
            return $res;
        } else {
            $res = new CArrayList();
            $query = $this->getQuery();
            /**
             * Использование глобального поиска и глобальных сортировок
             */
            if ($this->_useGlobalSearch) {
                // глобальный поиск
                $globalFilter = CRequest::getGlobalFilter();
                if ($globalFilter["field"] !== false) {
                    $condition = $query->getCondition();
                    if (is_numeric($globalFilter["value"])) {
                        if ($condition != "") {
                            $condition .= " AND ".$globalFilter["field"].'='.$globalFilter["value"];
                        } else {
                            $condition = $globalFilter["field"].'='.$globalFilter["value"];
                        }
                    } else {
                        if ($condition != "") {
                            $condition .= " AND ".$globalFilter["field"]." like '%".$globalFilter["value"]."%'";
                        } else {
                            $condition = $globalFilter["field"]." like '%".$globalFilter["value"]."%'";
                        }
                    }
                    $query->condition($condition);
                }
                // глобальные сортировки
                $globalOrder = CRequest::getGlobalOrder();
                if ($globalOrder["field"] !== false) {
                    $query->order($globalOrder["field"]." ".$globalOrder["direction"]);
                }
            }
            /**
             * Использование глобального ограничения доступа
             */
            if ($this->_isAclControlledSet) {
                $this->updateQueryForACLLimitations();
            }
            $items = $query->execute();
            foreach ($items->getItems() as $item) {
                $ar = new CActiveRecord($item);
                $ar->setTable($query->getTable());
                $res->add($ar->getId(), $ar);
            }
            return $res;
        }
    }
}
