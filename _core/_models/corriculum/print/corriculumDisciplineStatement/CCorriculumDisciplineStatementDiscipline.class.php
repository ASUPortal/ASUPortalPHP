<?php

class CCorriculumDisciplineStatementDiscipline extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Дисциплина заявки на учебную литературу дисциплины учебного плана";
    }

    public function getFieldDescription()
    {
        return "Используется при печати дисциплин учебного плана, принимает параметр id с Id дисциплины учебного плана";
    }

    public function getParentClassField()
    {

    }

    public function getFieldType()
    {
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
        $result = $contextObject->discipline->discipline->getValue();
        return $result;
    }
}