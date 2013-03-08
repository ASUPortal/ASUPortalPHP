<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 28.05.12
 * Time: 12:06
 * To change this template use File | Settings | File Templates.
 */
class CCalendar extends CActiveModel {
    private $_resource = null;
    private $_startTime = null;
    private $_endTime = null;
    private $_events = null;
    /**
     * Ресурс, к которому календарь привязан
     *
     * @param CResource $resource
     */
    public function setResource(CResource $resource) {
        $this->_resource = $resource;
        $this->setResourceId($resource->getId());
    }
    public function getResourceId() {
        return $this->getRecord()->getItemValue("resource_id");
    }
    /**
     * Ресурс, к которому календарь привязан
     *
     * @return CResource
     */
    public function getResource() {
        if (is_null($this->_resource)) {
            $ar = CActiveRecordProvider::getById(TABLE_RESOURCES, $this->getResourceId());
            $this->_resource = new CResource($ar);
        }
        return $this->_resource;
    }
    /**
     * Идентификатор ресурса, к которому календарь привязан
     *
     * @param $id
     */
    public function setResourceId($id) {
        $this->getRecord()->setItemValue("resource_id", $id);
    }
    /**
     * Календарь по умолчанию
     *
     * @param $default
     */
    public function setDefault($default) {
        if ($default) {
            $this->getRecord()->setItemValue("isDefault", "1");
        } else {
            $this->getRecord()->setItemValue("isDefault", "0");
        }
    }
    /**
     * Является ли публичным
     *
     * @param $public
     */
    public function setPublic($public) {
        if ($public) {
            $this->getRecord()->setItemValue("isPublic", "1");
        } else {
            $this->getRecord()->setItemValue("isPublic", "0");
        }
    }
    /**
     * Показывать детали или только время, когда занят
     *
     * @param $show
     */
    public function setShowNoDetails($show) {
        if ($show) {
            $this->getRecord()->setItemValue("showNoDetails", "1");
        } else {
            $this->getRecord()->setItemValue("showNoDetails", "0");
        }
    }
    /**
     * Название календаря
     *
     * @param $name
     */
    public function setName($name) {
        $this->getRecord()->setItemValue("name", $name);
    }
    public function getName() {
        return $this->getRecord()->getItemValue("name");
    }
    /**
     * Описание
     *
     * @param $desc
     */
    public function setDescription($desc) {
        $this->getRecord()->setItemValue("description", $desc);
    }
    /**
     * Является ли календарем по умолчанию
     *
     * @return bool
     */
    public function isDefault() {
        if ($this->getRecord()->getItemValue("isDefault") == "1") {
            return true;
        }
        return false;
    }
    public function setStartTime($time) {
        $this->_startTime = $time;
    }
    public function setEndTime($time) {
        $this->_endTime = $time;
    }
    public function getStartTime() {
        return $this->_startTime;
    }
    public function getEndTime() {
        return $this->_endTime;
    }
    /**
     * События текущего календаря на выбранный промежуток времени
     *
     * @return CArrayList
     */
    public function getEvents() {
        if (is_null($this->_events)) {
            $this->_events = new CArrayList();
            $cond = "resource_id=".$this->getResourceId()." and calendar_id=".$this->getId();
            if (!is_null($this->getStartTime())) {
                $cond .= " and eventStart >= '".date("Y-m-d h:n:s", $this->getStartTime())."'";
            }
            if (!is_null($this->getEndTime())) {
                $cond .= " and eventEnd <= '".date("Y-m-d h:n:s", $this->getEndTime())."'";
            }
            $q = new CQuery();
            $q->select(TABLE_EVENTS.".*")
            ->from(TABLE_EVENT_MEMBERSHIP)
            ->condition($cond)
            ->leftJoin(TABLE_EVENTS, TABLE_EVENTS.".id=".TABLE_EVENT_MEMBERSHIP.".event_id");
            $ars = $q->execute();

            foreach ($ars->getItems() as $item) {
                $ar = new CActiveRecord($item);
                $e = new CEvent($ar);
                $this->_events->add($e->getId(), $e);
            }
        }
        return $this->_events;
    }
}
