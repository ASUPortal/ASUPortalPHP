<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 29.09.12
 * Time: 10:41
 * To change this template use File | Settings | File Templates.
 */
class CRatingJSONController extends CBaseJSONController {
    /**
     * Копирование показателей из одного года в другой
     *
     * @return bool
     */
    public function actionCopyIndexes() {
        $indexes = $_GET['indexes'];
        $year = $_GET['year'];

        $year = CTaxonomyManager::getYear($year);
        if (is_null($year)) {
            return false;
        }
        foreach ($indexes as $index) {
            $index = CRatingManager::getRatingIndex($index);
            if (!is_null($index)) {
                if ($index->year->getId() !== $year->getId()) {
                    $newIndex = new CRatingIndex();
                    $newIndex->title = $index->title;
                    $newIndex->manager_class = $index->manager_class;
                    $newIndex->manager_method = $index->manager_method;
                    $newIndex->year_id = $year->getId();
                    $newIndex->person_method = $index->person_method;
                    $newIndex->isMultivalue = $index->isMultivalue;
                    $newIndex->save();
                    // копируем также значения
                    foreach ($index->getIndexValues()->getItems() as $value) {
                        $newValue = new CRatingIndexValue();
                        $newValue->index_id = $newIndex->getId();
                        $newValue->fromTaxonomy = $value->fromTaxonomy;
                        $newValue->title = $value->title;
                        $newValue->value = $value->value;
                        $newValue->evaluate_method = $value->evaluate_method;
                        $newValue->evaluate_code = $value->evaluate_code;
                        $newValue->save();
                    }
                }
            }
        }
    }
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
        foreach (CActiveRecordProvider::getWithCondition(TABLE_RATING_INDEXES, "title LIKE '%$indexes%'")->getItems() as $item) {
            $index = CRatingManager::getRatingIndex($item->getItemValue("id"));
            if (!is_null($index)) {
                $tmpRes->add($index->title, $index);
            }
        }
        foreach ($tmpRes->getItems() as $index) {
            $res[] = array(
                "label" => $index->title,
                "value" => $index->title
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
        foreach(CActiveRecordProvider::getWithCondition(TABLE_PERSON, "fio like '%".$name."%'")->getItems() as $item) {
            $person = CStaffManager::getPerson($item->getItemValue("id"));
            $flag = false;
            foreach ($years->getItems() as $year) {
                if ($person->getRatingIndexesByYear($year)->getCount() > 0) {
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
                foreach (CRatingManager::getRatingIndexesByName($item)->getItems() as $index) {
                    if ($years->hasElement($index->year_id)) {
                        $indexes->add($index->getId(), $index);
                    }
                }
            }
        } else {
            foreach ($years->getItems() as $year) {
                foreach (CRatingManager::getRatingIndexesByYear($year)->getItems() as $index) {
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
                    foreach ($person->getRatingIndexesByYear($year)->getItems() as $index) {
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
                foreach ($person->getRatingIndexesByYear($year)->getItems() as $index) {
                    if ($indexes->hasElement($index->getId())) {
                        $resIndexes[$index->title] = $index->title;
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
                    foreach ($person->getRatingIndexesByYear($year)->getItems() as $index) {
                        if ($index->title == $key) {
                            $indexValue = $index->getIndexValue();
                        }
                    }
                    $data[] = $indexValue;
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
