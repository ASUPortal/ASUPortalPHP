<?php

class CCorriculumDisciplineStatementYearOfPublishing extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Год издания заявки на учебную литературу дисциплины учебного плана";
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
        $result = $contextObject->year_of_publishing;
        return $result;
    }
}