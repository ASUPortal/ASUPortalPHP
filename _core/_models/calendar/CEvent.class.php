<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 28.05.12
 * Time: 14:23
 * To change this template use File | Settings | File Templates.
 */
class CEvent extends CActiveModel {
    private $_members;
    private $_calendar;

    public function setName($name) {
        $this->getRecord()->setItemValue("name", $name);
    }
    public function getName() {
        return $this->getRecord()->getItemValue("name");
    }
    public function setDescription($desc) {
        $this->getRecord()->setItemValue("description", $desc);
    }
    public function setStartTime($time) {
        $t = strtotime($time);
        $this->getRecord()->setItemValue("eventStart", date("Y-m-d h:n:s", $t));
    }
    public function getStartTime() {
        if (strtotime($this->getRecord()->getItemValue("eventStart")) == 0) {
            return null;
        }
        return strtotime($this->getRecord()->getItemValue("eventStart"));
    }
    public function setEndTime($time) {
        $t = strtotime($time);
        $this->getRecord()->setItemValue("eventEnd", date("Y-m-d h:n:s", $t));
    }
    public function getEndTime() {
        if (strtotime($this->getRecord()->getItemValue("eventEnd")) == 0) {
            return null;
        }
        return strtotime($this->getRecord()->getItemValue("eventEnd"));
    }
    public function setMembers(CArrayList $members) {
        $this->_members = $members;
    }
    public function setCalendar(CCalendar $calendar) {
        $this->_calendar = $calendar;
    }
    /**
     * @return CCalendar
     */
    public function getCalendar() {
        if (is_null($this->_calendar)) {
            // todo
        }
        return $this->_calendar;
    }
    public function save() {
        parent::save();

        // берем все старые записи об участии и создаем их заново
        $q = new CQuery();
        $q->remove(TABLE_EVENT_MEMBERSHIP)
        ->condition("event_id=".$this->getId())
        ->execute();

        // добавляем заново
        foreach ($this->getMembers()->getItems() as $item) {
            $q = new CQuery();
            $q->insert(TABLE_EVENT_MEMBERSHIP, array(
                "resource_id" => $item->getId(),
                "event_id" => $this->getId(),
                "calendar_id" => $this->getCalendar()->getId()
            ));
            $q->execute();
        }
    }
    /**
     * Участники события
     *
     * @return CArrayList
     */
    public function getMembers() {
        if (is_null($this->_members)) {
            $this->_members = new CArrayList();
        }
        return $this->_members;
    }
    public function toArrayForJSON() {
        $r = array(
            "id" => $this->getId(),
            "title" => $this->getName()
        );
        if (!is_null($this->getStartTime())) {
            $r['start'] = date("Y-m-d", $this->getStartTime());
        }
        if (!is_null($this->getEndTime())) {
            $r['end'] = date("Y-m-d", $this->getEndTime());
        }
        $r['url'] = WEB_ROOT."_modules/_calendar/?action=view&id=".$this->getId();
        return $r;
    }
}
