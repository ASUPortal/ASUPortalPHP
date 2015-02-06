<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.02.14
 * Time: 20:58
 * To change this template use File | Settings | File Templates.
 */

class CSearchCatalogStaff extends CAbstractSearchCatalog{
    public function actionGetCatalogProperties()
    {
        $properties = array();
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy("person_types");
        /**
         * @var $term CTerm
         */
        $properties["order"] = "С действующим приказом";
        foreach ($taxonomy->getTerms()->getItems() as $term) {
            $properties[$term->getId()] = $term->getValue();
        }
        return $properties;
    }

    public function actionGetDefaultCatalogProperties()
    {
        $properties = array();
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy("person_types");
        /**
         * @var $term CTerm
         */
        foreach ($taxonomy->getTerms()->getItems() as $term) {
            if ($term->getValue() == TYPE_PPS) {
                $properties[] = $term->getId();
            } elseif ($term->getValue() == "учебно-вспомогательный персонал") {
                $properties[] = $term->getId();
            } elseif ($term->getValue() == "аспирант") {
                $properties[] = $term->getId();
            }
        }
        $properties[] = "order";
        return $properties;
    }


    public function actionTypeAhead($lookup)
    {
        $result = array();
        // выбор сотрудников
        $query = new CQuery();
        $query->select("person.id as id, person.fio as name")
            ->from(TABLE_PERSON." as person")
            ->condition("person.fio like '%".$lookup."%'")
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        // выбор сотрудников
        $person = CStaffManager::getPerson($id);
        if (!is_null($person)) {
            $result[$person->getId()] = $person->getName();
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        // выбор сотрудников
        $withOrder = false;
        if (in_array("order", $this->properties)) {
            $withOrder = true;
            unset($this->properties[array_search("order", $this->properties)]);
        }
        // если ничего не выбрано, то выдаем всех
        $personsArray = array();
        if (count($this->properties) == 0) {
            foreach (CStaffManager::getAllPersons()->getItems() as $person) {
                $result[$person->getId()] = $person->getName();
                $personsArray[] = $person;
            }
        } else {
            $types = new CArrayList();
            foreach ($this->properties as $type) {
                $types->add($type, $type);
            }
            foreach (CStaffManager::getPersonsWithTypes($types)->getItems() as $person) {
                $result[$person->getId()] = $person->getName();
                $personsArray[] = $person;
            }
        }
        if ($withOrder) {
            /**
             * @var $person CPerson
             */
            foreach ($personsArray as $person) {
                if (!$person->hasActiveOrder()) {
                    unset($result[$person->getId()]);
                }
            }
        }
        return $result;
    }

    public function actionGetCreationActionUrl()
    {
        // TODO: Implement actionGetCreationActionUrl() method.
    }
}