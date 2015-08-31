<?php

class CRatingPublicationsJSONController extends CBaseJSONController {
    /**
     * Автоподстановка годов
     */
    public function actionGetYears() {
    	$years = $_GET["data"]["term"];
    	$res = array();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_YEARS, "name LIKE '%".$years."%'")->getItems() as $item) {
    		$res[] = array(
    				"label" => $item->getItemValue("name"),
    				"value" => $item->getItemValue("name")
    		);
    	}
    	echo json_encode($res);
    }
    /**
     * Автоподстановка индексов
     */
    public function actionGetIndexes() {
        $indexes = $_GET["data"]["term"];
        $res = array();
        $tmpRes = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_PUBLICATIONS_TYPES, "name LIKE '%$indexes%'")->getItems() as $item) {
            $index = CBaseManager::getPublicationByTypes($item->getItemValue("id"));
            if (!is_null($index)) {
                $tmpRes->add($index->name, $index);
            }
        }
        foreach ($tmpRes->getItems() as $index) {
            $res[] = array(
                "label" => $index->name,
                "value" => $index->name
            );
        }
        echo json_encode($res);
    }
    /**
     * Автоподстановка людей
     * Выбираем только людей, у которых есть показатели
     */
    public function actionGetPersons() {
        $data = $_GET["data"];
        $name = $data["term"];
        // определяем, в каких годах искать
        $years = new CArrayList();
        if (array_key_exists("years", $data)) {
            foreach ($data['years'] as $item) {
                $year = CTaxonomyManager::getYear($item);
                if (!is_null($year)) {
                    $years->add($year->getId(), $year);
                }
            }
        } else {
            $years->add(CUtils::getCurrentYear()->getId(), CUtils::getCurrentYear());
        }
        $res = array();
        
        $personQuery->select("person.id, person.fio")
        ->from(TABLE_PERSON." as person")
        ->innerJoin(TABLE_PUBLICATION_BY_PERSONS." as p", "p.kadri_id = person.id")
        ->order("person.fio asc");
        
        foreach(CActiveRecordProvider::getWithCondition(TABLE_PERSON, "fio like '%".$name."%'")->getItems() as $item) {
            $person = CStaffManager::getPerson($item->getItemValue("id"));
            $flag = false;
            foreach ($years->getItems() as $year) {
                if ($person->getPublications($year)->getCount() > 0) {
                    $flag = true;
                }
            }
            if ($flag) {
                $res[] = array(
                    "label" => $person->getName(),
                    "value" => $person->getName()
                );
            }
        }
        echo json_encode($res);
    }
    public function actionGetDataForChart() {
        $data = array();
        if (array_key_exists("data", $_POST)) {
            $data = $_POST["data"];
        }
        // определяем, в каких годах искать
        $years = new CArrayList();
        if (array_key_exists("years", $data)) {
            foreach ($data['years'] as $item) {
                $year = CTaxonomyManager::getYear($item);
                if (!is_null($year)) {
                    $years->add($year->getId(), $year);
                }
            }
        } else {
            $years->add(CUtils::getCurrentYear()->getId(), CUtils::getCurrentYear());
        }
        // по каким показателям показывать
        $indexes = new CArrayList();
        if (array_key_exists("indexes", $data)) {
            foreach ($data["indexes"] as $item) {
                foreach (CStaffManager::getPublicationsByType($item)->getItems() as $index) {
                	foreach ($years->getItems() as $year) {
                		if ($index->year == date("Y", strtotime($year->date_start)) or $index->year == date("Y", strtotime($year->date_end))) {
                			$indexes->add($index->getId(), $index);
                		}
                	}
                }
            }
        } else {
            foreach ($years->getItems() as $year) {
                foreach (CStaffManager::getPublicationsByYear($year)->getItems() as $index) {
                    $indexes->add($index->getId(), $index);
                }
            }
        }
        // по каким людям показывать
        $persons = new CArrayList();
        if (array_key_exists("persons", $data)) {
            foreach ($data["persons"] as $item) {
                $person = CStaffManager::getPerson($item);
                if (!is_null($person)) {
                    $persons->add($person->getId(), $person);
                }
            }
        } else {
            // показывать по всем, у кого показатели есть в указанных годах
            foreach (CStaffManager::getAllPersons()->getItems() as $person) {
                foreach ($years->getItems() as $year) {
                    foreach ($person->getPublications($year)->getItems() as $index) {
                        if ($indexes->hasElement($index->getId())) {
                            $persons->add($person->getId(), $person);
                        }
                    }
                }
            }
        }
        $res = array();
        // начинаем собирать инфу по людям
        // подписи к осям
        $axis = array();
        $i = 0;
        foreach ($persons->getItems() as $person) {
            $i++;
            $axis[] = $person->getName();
            //$axis[] = $i;
        }
        // все показатели, которые есть у выбранных людей (id всех показателей)
        // за все годы
        $resIndexes = array();
        foreach ($persons->getItems() as $person) {
            foreach ($years->getItems() as $year) {
                foreach ($person->getPublications($year)->getItems() as $index) {
                    if ($indexes->hasElement($index->getId())) {
                    	if (!is_null($index->type)) {
                    		$resIndexes[$index->type->getValue()] = $index->type->getValue();
                    	}
                    }
                }
            }
        }
        $indicators = array();
        // данные по годам
        // данные должны возвращаться в том же порядке, в котором у нас идут люди
        foreach ($resIndexes as $key=>$value) {
            foreach ($years->getItems() as $year) {
                $data = array();
                // собираем данные по каждому человеку
                foreach ($persons->getItems() as $person) {
                    $indexValue = 0;
                    foreach ($person->getPublications($year)->getItems() as $index) {
                    	if (!is_null($index->type)) {
                    		if ($index->type->getValue() == $key) {
                    			$indexValue = $index->type->weight;
                    		}
                    	}
                        
                    }
                    $data[] = (float) $indexValue;
                }
                $indicator = array(
                    "name" => $value." (".$year->name.")",
                    "data" => $data,
                    "stack" => $year->name
                );
                $indicators[] = $indicator;
            }
        }
        $res["axis"] = $axis;
        $res["series"] = $indicators;
        echo json_encode($res);
    }
}
