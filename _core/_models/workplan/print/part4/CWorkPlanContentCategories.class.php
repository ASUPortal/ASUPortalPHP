<?php

class CWorkPlanContentCategories extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Содержание разделов";
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
        if (!is_null($contextObject->modules)) {
        	foreach ($contextObject->modules->getItems() as $module) {
        		if (!is_null($module->sections)) {
        			foreach ($module->sections->getItems() as $row) {
        				$dataRow = array();
        				$dataRow[0] = $row->sectionIndex;
        				$dataRow[1] = $row->name;
        				$dataRow[2] = $row->content;
        				$dataRow[3] = implode(", ", $row->controls->getItems());
        				$result[] = $dataRow;
        			}
        		}
        	}
        }
        return $result;
    }
}