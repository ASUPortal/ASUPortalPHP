<?php

class CCorriculumDisciplineStatementGrif extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Гриф заявки на учебную литературу дисциплины учебного плана";
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
        $result = $contextObject->grif;
        return $result;
    }
}