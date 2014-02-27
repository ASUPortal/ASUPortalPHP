<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.02.14
 * Time: 21:12
 * To change this template use File | Settings | File Templates.
 */

class CSearchCatalogTaxonomy implements ISearchCatalogInterface{
    private $_catalog;

    public function actionTypeAhead($lookup)
    {
        $result = array();
        // таксономия
        $taxonomy = CTaxonomyManager::getTaxonomy($this->_catalog);
        $query = new CQuery();
        $query->select("distinct(taxonomy.id) as id, taxonomy.name as name, taxonomy.taxonomy_id as tax")
            ->from(TABLE_TAXONOMY_TERMS." as taxonomy")
            ->condition("taxonomy.name like '%".$lookup."%' and taxonomy.taxonomy_id =".$taxonomy->getId())
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        $term = CTaxonomyManager::getTerm($id);
        if (!is_null($term)) {
            $result[$term->getId()] = $term->getValue();
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $taxonomy = CTaxonomyManager::getTaxonomy($this->_catalog);
        return $taxonomy->getTermsList();
    }

    function __construct($catalog)
    {
        $this->_catalog = $catalog;
    }

}