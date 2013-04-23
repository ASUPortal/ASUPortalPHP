<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 06.10.12
 * Time: 18:49
 * To change this template use File | Settings | File Templates.
 */
class CRecordSet {
    private $_items = null;
    private $_page = null;
    private $_pageSize = null;
    private $_paginator = null;
    private $_query = null;
    private $_manualAdded = false;
    /**
     * @return CArrayList
     */
    private function getItemsCache() {
        if (is_null($this->_items)) {
            $this->_items = new CArrayList();
        }
        return $this->_items;
    }
    /**
     * Для совместимости со старым кодом. Все записи
     *
     * @return array
     */
    public function getItems() {
        // если запрос еще не выполнялся - выполним его
        if (!is_null($this->getQuery())) {
            $res = $this->getQuery()->execute();
            foreach ($res->getItems() as $item) {
                $ar = new CActiveRecord($item);
                $ar->setTable($this->getQuery()->getTable());
                $this->getItemsCache()->add($ar->getId(), $ar);
            }
        }
        return $this->getItemsCache()->getItems();
    }
    /**
     * Добавление записи в множество
     *
     * @param $key
     * @param $value
     */
    public function add($key, $value) {
        $this->_manualAdded = true;
        $this->getItemsCache()->add($key, $value);
    }

    /**
     * Установить значение параметра ручного добавления
     *
     * @param $val
     */
    public function setManualAdd($val) {
        $this->_manualAdded = $val;
    }
    /**
     * Размер страницы
     *
     * @return int
     */
    public function getPageSize() {
        if (is_null($this->_pageSize)) {
            $this->_pageSize = 20;
        }
        if (CRequest::getInt("page_size") !== 0) {
            $this->_pageSize = CRequest::getInt("page_size");
        }
        return $this->_pageSize;
    }

    /**
     * Установить размер страницы
     *
     * @param $size
     */
    public function setPageSize($size) {
        $this->_pageSize = $size;
    }
    /**
     * Номер текущей страницы
     *
     * @return int
     */
    public function getCurrentPage() {
        $this->_page = 1;
        if (CRequest::getInt("page") !== 0) {
            $this->_page = CRequest::getInt("page");
        }
        return $this->_page;
    }

    /**
     * Число извлеченных записей.
     * Оставлено для совместимости. Для пагинатора используется getItemsCont()
     *
     * @return int
     */
    public function getCount() {
        $this->getItems();
        return $this->getItemsCache()->getCount();
    }

    /**
     * Количество записей в таблице
     *
     * @return int
     */
    public function getItemsCount() {
        if ($this->_manualAdded) {
            return $this->getCount();
        } else {
            $query = new CQuery();
            if (mb_strpos(mb_strtolower($this->getQuery()->getFields()), "distinct") === false) {
                $query->select("count(".$this->getTableAlias().".id) as cnt");
            } else {
                $query->select("count(distinct ".$this->getTableAlias().".id) as cnt");
            }
            $query->from($this->getQuery()->getTable())
            ->condition($this->getQuery()->getCondition());
            foreach ($this->getQuery()->getInnerJoins()->getItems() as $key=>$value) {
                $query->innerJoin($key, $value);
            }
            foreach ($this->getQuery()->getLeftJoins() as $key=>$value) {
                $query->leftJoin($key, $value);
            }
            $arr = $query->execute();
            if ($arr->getCount() > 0) {
                $item = $arr->getFirstItem();
                return $item["cnt"];
            } else {
                return 0;
            }
        }
    }
    /**
     * Разбитый на страницы набор данных
     *
     * @return CArrayList
     */
    public function getPaginated() {
        /**
         * Разбивка на страницы на случай, если записи добавлялись вручную
         * и на случай, если рекордсет получен из таблицы
         */
        if ($this->_manualAdded) {
            $res = new CArrayList();
            $i = 0;
            $start = ($this->getCurrentPage() - 1) * $this->getPageSize();
            $end = ($this->getCurrentPage() * $this->getPageSize());
            foreach ($this->getItems() as $key=>$value) {
                if ($i >= $start && $i < $end) {
                    $res->add($key, $value);
                }
                $i++;
            }
            return $res;
        } else {
            $res = new CArrayList();
            $start = ($this->getCurrentPage() - 1) * $this->getPageSize();
            $query = $this->getQuery();
            $query->limit($start, $this->getPageSize());
            $items = $query->execute();
            foreach ($items->getItems() as $item) {
                $ar = new CActiveRecord($item);
                $ar->setTable($query->getTable());
                $res->add($ar->getId(), $ar);
            }
            return $res;
        }
    }
    /**
     * @return CPaginator
     */
    public function getPaginator() {
        if (is_null($this->_paginator)) {
            $this->_paginator = new CPaginator($this);
        }
        return $this->_paginator;
    }

    /**
     * Сохраняем объект запроса для отложенной инициализации
     *
     * @param CQuery $query
     */
    public function setQuery($query) {
        $this->_query = $query;
    }

    /**
     * Возвращаем объект запроса
     *
     * @return CQuery
     */
    private function getQuery() {
        return $this->_query;
    }
    private function getTableAlias() {
        if (strpos($this->getQuery()->getTable(), " as ")) {
            return substr($this->getQuery()->getTable(), strpos($this->getQuery()->getTable(), "as") + 3);
        }
        return $this->getQuery()->getTable();
    }
}
