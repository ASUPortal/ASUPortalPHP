<?php

class CCorriculumWorkPlansStatus extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Статусы рабочих программ учебного плана";
    }

    public function getFieldDescription()
    {
        return "Используется при печати учебного плана, принимает параметр id с Id учебного плана";
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
		if (!is_null($contextObject->cycles)) {
			foreach ($contextObject->cycles->getItems() as $cycle) {
				if (!is_null($cycle->disciplines)) {
					foreach ($cycle->disciplines->getItems() as $discipline) {
						if (!is_null($discipline->plans)) {
							foreach ($discipline->plans->getItems() as $plan) {
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
								if ($plan->statusWorkplan == "0" or is_null($plan->statusWorkplan)) {
									$dataRow[3] = "Нет комментария";
								} else {
									$dataRow[3] = $plan->statusWorkplan->getValue();
								}
								if ($plan->statusOnPortal == "0" or is_null($plan->statusOnPortal)) {
									$dataRow[4] = "Нет комментария";
								} else {
									$dataRow[4] = $plan->statusOnPortal->getValue();
								}
								$result[] = $dataRow;
							}
						}	
					}
				}
			}
		}
        return $result;
    }
}