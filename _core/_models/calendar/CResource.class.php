<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 28.05.12
 * Time: 9:19
 * To change this template use File | Settings | File Templates.
 */
class CResource extends CActiveModel {
    private $_resource;
    private $_calendars;
    /**
     * Название ресурса
     */
    public function setName() {
        $this->getRecord()->setItemValue("name", CRequest::getString("name"));
    }
    /**
     * Название ресурса
     *
     * @return mixed
     */
    public function getName() {
        return $this->getRecord()->getItemValue("name");
    }
    /**
     * Родительский ресурса
     *
     * @return mixed
     */
    public function getResource() {
        if (is_null($this->_resource)) {
            if ($this->getType() == "kadri") {
                $this->_resource = CStaffManager::getPersonById($this->getResourceId());
            }
        }
        return $this->_resource;
    }
    /**
     * Тип ресурса
     */
    public function setType() {
        $this->getRecord()->setItemValue("type", CRequest::getString("type"));
    }
    /**
     * Ресурс
     */
    public function setResourceId() {
        $this->getRecord()->setItemValue("resource_id", CRequest::getInt("resource_id"));
        if ($this->getType() == "kadri") {
            $this->_resource = CStaffManager::getPersonById($this->getResourceId());
        }
    }
    /**
     * Идентификатор связанного ресурса
     *
     * @return mixed
     */
    public function getResourceId() {
        return $this->getRecord()->getItemValue("resource_id");
    }
    /**
     * Тип
     *
     * @return mixed
     */
    public function getType() {
        return $this->getRecord()->getItemValue("type");
    }
    /**
     * Календари данного ресурса
     *
     * @return CArrayList
     */
    public function getCalendars() {
        if (is_null($this->_calendars)) {
            $this->_calendars = new CArrayList();
            $ars = CActiveRecordProvider::getWithCondition(TABLE_CALENDARS, "resource_id=".$this->getId());
            foreach ($ars->getItems() as $i) {
                $item = new CCalendar($i);
                $this->_calendars->add($item->getId(), $item);
            }
        }
        return $this->_calendars;
    }
    /**
     * Календари данного ресурса в виде массива для подстановки
     *
     * @return array
     */
    public function getCalendarsList() {
        $r = array();
        foreach ($this->getCalendars()->getItems() as $i) {
            $r[$i->getId()] = $i->getName();
        }
        return $r;
    }
    /**
     * Возвращает календарь по умолчанию
     *
     * @return CCalendar
     */
    public function getDefaultCalendar() {
        foreach ($this->getCalendars()->getItems() as $i) {
            if ($i->isDefault()) {
                return $i;
            }
        }
    }
}
