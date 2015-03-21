<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 21.03.15
 * Time: 19:15
 */

class CTaxonomyCatalog extends CAbstractSearchCatalog{

    public function actionTypeAhead($lookup)
    {
        $result = array();
        // выбор сотрудников
        $query = new CQuery();
        $query->select("taxonomy.id as id, taxonomy.name as name")
            ->from(TABLE_TAXONOMY." as taxonomy")
            ->condition("taxonomy.name like '%".$lookup."%'")
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        /**
         * @var $obj CTaxonomy
         */
        $obj = $this->actionGetObject($id);
        if (!is_null($obj)) {
            $result[$obj->getId()] = $obj->getName();
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        $query = new CQuery();
        $query->select("taxonomy.id as id, taxonomy.name as name")
            ->from(TABLE_TAXONOMY." as taxonomy");
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetCreationActionUrl()
    {
        // TODO: Implement actionGetCreationActionUrl() method.
    }

    public function actionGetObject($id)
    {
        $taxonomy = CTaxonomyManager::getTaxonomy($id);
        return $taxonomy;
    }
}