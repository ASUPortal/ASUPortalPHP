<?php
/**
 * Вид занятия в расписании
 */
class CScheduleKindWork extends CTerm {
    protected $_table = TABLE_SCHEDULE_KIND_WORK;
    
    /**
     * Значение псевдонима термина
     *
     * @return string
     */
    public function getAlias() {
        return $this->getRecord()->getItemValue("comment");
    }
}
