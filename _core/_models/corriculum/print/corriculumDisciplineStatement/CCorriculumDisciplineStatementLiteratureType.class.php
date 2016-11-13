<?php

class CCorriculumDisciplineStatementLiteratureType extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Тип литературы заявки на учебную литературу дисциплины учебного плана";
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
        $result = "";
        if ($contextObject->literature_type == 1) {
    		$result = "основной";
        } elseif ($contextObject->literature_type == 2) {
    		$result = "дополнительной";
        }
        return $result;
    }
}