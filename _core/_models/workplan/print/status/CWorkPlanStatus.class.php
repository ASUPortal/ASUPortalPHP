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
			if ($plan->comment_file == "0" or is_null($plan->commentFile)) {
				$dataRow[2] = "Нет комментария";
			} else {
				$dataRow[2] = $plan->commentFile->getValue();
			}
			if ($plan->status_on_portal == "0" or is_null($plan->statusOnPortal)) {
				$dataRow[3] = "Нет комментария";
			} else {
				$dataRow[3] = $plan->statusOnPortal->getValue();
			}
			if ($plan->status_workplan_library == "0" or is_null($plan->statusWorkplanLibrary)) {
				$dataRow[4] = "–";
			} else {
				$dataRow[4] = $plan->statusWorkplanLibrary->getValue();
			}
			if ($plan->status_workplan_lecturer == "0" or is_null($plan->statusWorkplanLecturer)) {
				$dataRow[5] = "–";
			} else {
				$dataRow[5] = $plan->statusWorkplanLecturer->getValue();
			}
			if ($plan->status_workplan_head_of_department == "0" or is_null($plan->statusWorkplanHeadOfDepartment)) {
				$dataRow[6] = "–";
			} else {
				$dataRow[6] = $plan->statusWorkplanHeadOfDepartment->getValue();
			}
			if ($plan->status_workplan_nms == "0" or is_null($plan->statusWorkplanNMS)) {
				$dataRow[7] = "–";
			} else {
				$dataRow[7] = $plan->statusWorkplanNMS->getValue();
			}
			if ($plan->status_workplan_dean == "0" or is_null($plan->statusWorkplanDean)) {
				$dataRow[8] = "–";
			} else {
				$dataRow[8] = $plan->statusWorkplanDean->getValue();
			}
			if ($plan->status_workplan_prorektor == "0" or is_null($plan->statusWorkplanProrektor)) {
				$dataRow[9] = "–";
			} else {
				$dataRow[9] = $plan->statusWorkplanProrektor->getValue();
			}
			$result[] = $dataRow;
		}
        return $result;
    }
}