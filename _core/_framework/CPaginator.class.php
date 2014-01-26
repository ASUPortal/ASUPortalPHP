<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 06.10.12
 * Time: 19:30
 * To change this template use File | Settings | File Templates.
 */
class CPaginator {
    private $_recordSet = null;
    private $_pagesCount = null;
    public function __construct(CRecordSet $set) {
        $this->_recordSet = $set;
    }
    /**
     * @return CRecordSet
     */
    public function getRecordSet() {
        return $this->_recordSet;
    }

    /**
     * Количество записей на странице для текущего набора записей
     *
     * @return int
     */
    public function getCurrentPageSize() {
        return $this->getRecordSet()->getPageSize();
    }

    /**
     * Доступные размеры страниц
     *
     * @return array
     */
    public function getPageSizes() {
        return array(
            20 => 20,
            50 => 50,
            100 => 100,
            PAGINATION_ALL => "Все"
        );
    }
    public function getPagesCount() {
        if (is_null($this->_pagesCount)) {
            if ($this->getCurrentPageSize() == PAGINATION_ALL) {
                $this->_pagesCount = 1;
            } else {
                $records = $this->getRecordSet()->getItemsCount();
                $pageSize = $this->getRecordSet()->getPageSize();
                if (($records / $pageSize) == ceil($records / $pageSize)) {
                    $this->_pagesCount =  ($records / $pageSize);
                } else {
                    $this->_pagesCount =  ceil($records / $pageSize);
                }
            }
        }
        return $this->_pagesCount;
    }

    /**
     * Список страниц для пагинатора. Чтобы совсем много не было
     * отображается << 5 страниц - текущая - 5 страниц >>
     *
     * @param $action
     * @return array
     */
    public function getPagesList($action) {
        $res = array();
        $start = 1;
        $end = $this->getPagesCount();
        if ($this->getCurrentPageNumber() > 5) {
            $start = $this->getCurrentPageNumber() - 5;
            $res["Первая"] = $action."&page=1&page_size=".$this->getRecordSet()->getPageSize();
            if ($this->getCurrentPageNumber() !== 1) {
                $res["<<"] = $action."&page=".($this->getCurrentPageNumber() - 1)."&page_size=".$this->getRecordSet()->getPageSize();
            }
        }
        if ($end - $this->getCurrentPageNumber() > 5) {
            $end = $this->getCurrentPageNumber() + 5;
        }
        for ($i = $start; $i <= $end; $i++) {
            $res[$i] = $action."&page=".$i."&page_size=".$this->getRecordSet()->getPageSize();
        }
        if ($end != $this->getCurrentPageNumber()) {
            $res[">>"] = $action."&page=".($this->getCurrentPageNumber() + 1)."&page_size=".$this->getRecordSet()->getPageSize();
            $res["Последняя"] = $action."&page=".$this->getPagesCount()."&page_size=".$this->getRecordSet()->getPageSize();
        }
        return $res;
    }
    /**
     * Номер текущей страницы
     *
     * @return int
     */
    public function getCurrentPageNumber() {
        return $this->getRecordSet()->getCurrentPage();
    }
}
