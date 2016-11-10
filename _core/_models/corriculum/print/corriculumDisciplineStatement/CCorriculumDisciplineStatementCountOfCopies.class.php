<?php

class CCorriculumDisciplineStatementCountOfCopies extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Количество экземпляров заявки на учебную литературу дисциплины учебного плана";
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
        $result = $contextObject->count_of_copies;
        return $result;
    }
}