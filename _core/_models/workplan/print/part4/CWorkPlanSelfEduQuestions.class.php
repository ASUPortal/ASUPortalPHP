<?php

class CWorkPlanSelfEduQuestions extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Вопросы для самостоятельного изучения разделов дисциплины";
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
        $dataRow = array();
        foreach ($contextObject->categories->getItems() as $category) {
        	foreach ($category->sections->getItems() as $section) {
        		$questionExist = false;
        		foreach ($section->loadsDisplay->getItems() as $load) {
        			if ($load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK) {
        				if (!$load->topicsDisplay->isEmpty()) {
        					$questionExist = true;
        				}
        			}
        		}
        		if ($questionExist) {
        			$dataRow[] = "Тема ".$section->sectionIndex.". ".$section->name;
        			$dataRow[] = "Вопросы для самостоятельного изучения:";
        		}
        		$i = 1;
        		foreach ($section->loadsDisplay->getItems() as $load) {
        			if ($load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK) {
        				foreach ($load->topicsDisplay as $topic) {
        					$number = $i;
        					$dataRow[] = $number.". ".$topic->title;
        					$i++;
        				}
        			}
        		}
        		$taskExist = false;
        		if (!$section->calculationTasks->isEmpty()) {
        			$taskExist = true;
        		}
        		if ($taskExist and !$questionExist) {
        			$dataRow[] = "Тема ".$section->sectionIndex.". ".$section->name;
        		}
        		if ($taskExist) {
        			$dataRow[] = "Расчётные задания (задачи и пр.):";
        		}
        		if (!$taskExist and $questionExist) {
        			$dataRow[] = "Расчётные задания (задачи и пр.): не предусмотрены";
        		}
        		$n = 1;
        		foreach ($section->calculationTasks->getItems() as $calculationTask) {
        			$number = $n;
        			$dataRow[] = $number.". ".$calculationTask->task;
        			$n++;
        		}
        		if ($questionExist or $taskExist) {
        			$dataRow[] = "";
        		}
        	}
        }
        foreach ($dataRow as $key=>$value) {
        	$result[] = array($dataRow[$key]);
        }
        return $result;
    }
}