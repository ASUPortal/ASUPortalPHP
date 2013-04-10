<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 10.04.13
 * Time: 19:19
 * To change this template use File | Settings | File Templates.
 */

class CTaxonomyLegacy extends CTaxonomy{
    protected $_table = TABLE_TAXONOMIES_LEGACY;

    /**
     * @return mixed|null
     */
    public function getName() {
        return $this->comment;
    }

    /**
     * Таблица, из которой берем таксономию
     *
     * @return mixed|null
     */
    private function getTableName() {
        return $this->sprav_name;
    }
    protected function getCacheTerms() {
        if (is_null($this->_cacheTerms)) {
            $this->_cacheTerms = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable($this->getTableName())->getItems() as $ar) {
                $term = new CTerm($ar);
                $this->_cacheTerms->add($term->getId(), $term);
            }
        }
        return $this->_cacheTerms;
    }
}