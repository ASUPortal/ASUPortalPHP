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
			if ($plan->status_workplan_bibl == "0" or is_null($plan->statusWorkplanBibl)) {
				$dataRow[4] = "–";
			} else {
				$dataRow[4] = $plan->statusWorkplanBibl->getValue();
			}
			if ($plan->status_workplan_prepod == "0" or is_null($plan->statusWorkplanPrepod)) {
				$dataRow[5] = "–";
			} else {
				$dataRow[5] = $plan->statusWorkplanPrepod->getValue();
			}
			if ($plan->status_workplan_zav_kaf == "0" or is_null($plan->statusWorkplanZavKaf)) {
				$dataRow[6] = "–";
			} else {
				$dataRow[6] = $plan->statusWorkplanZavKaf->getValue();
			}
			if ($plan->status_workplan_nms == "0" or is_null($plan->statusWorkplanNMS)) {
				$dataRow[7] = "–";
			} else {
				$dataRow[7] = $plan->statusWorkplanNMS->getValue();
			}
			if ($plan->status_workplan_dekan == "0" or is_null($plan->statusWorkplanDekan)) {
				$dataRow[8] = "–";
			} else {
				$dataRow[8] = $plan->statusWorkplanDekan->getValue();
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