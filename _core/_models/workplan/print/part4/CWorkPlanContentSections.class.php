<?php

class CWorkPlanContentSections extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Содержание разделов дисциплины (для аннотации рабочей программы)";
    }

    public function getFieldDescription()
    {
        return "Используется при печати рабочей программы, принимает параметр id с Id рабочей программы";
    }

    public function getParentClassField()
    {

    }

    public function getFieldType()
    {
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
        $result = array();
        if (!is_null($contextObject->categories)) {
        	foreach ($contextObject->categories->getItems() as $category) {
        		if (!is_null($category->sections)) {
        			foreach ($category->sections->getItems() as $row) {
        				$dataRow = array();
        				$dataRow[0] = $row->sectionIndex;
        				$dataRow[1] = $row->name.": ".$row->content;
        				$result[] = $dataRow;
        			}
        		}
        	}
        }
        return $result;
    }
}