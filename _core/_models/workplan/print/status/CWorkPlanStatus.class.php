<?php

class CWorkPlanStatus extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Статус рабочей программы";
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
		$plans = new CArrayList();
		$arr = explode(":", CRequest::getString("id"));
		foreach ($arr as $id) {
			$plan = CWorkPlanManager::getWorkplan($id);
			if (!is_null($plan)) {
				$plans->add($plan->getId(), $plan);
			}
		}
		foreach ($plans->getItems() as $plan) {
			$dataRow = array();
			$dataRow[0] = $plan->discipline->getValue();
			$authors = array();
			if (!is_null($plan->authors)) {
				foreach ($plan->authors->getItems() as $author) {
					$authors[] = $author->getNameShort();
				}
			}
			$dataRow[1] = implode(", ", $authors);
			if ($plan->comment_file == 0 or is_null($plan->commentFile)) {
				$dataRow[2] = "Нет комментария";
			} else {
				$dataRow[2] = $plan->commentFile->getValue();
			}
			if ($plan->statusWorkplan == 0 or is_null($plan->statusWorkplan)) {
				$dataRow[3] = "Нет комментария";
			} else {
				$dataRow[3] = $plan->statusWorkplan->getValue();
			}
			if ($plan->statusOnPortal == 0 or is_null($plan->statusOnPortal)) {
				$dataRow[4] = "Нет комментария";
			} else {
				$dataRow[4] = $plan->statusOnPortal->getValue();
			}
			$result[] = $dataRow;
		}
        return $result;
    }
}