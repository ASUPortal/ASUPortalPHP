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
    protected $_task = null;

    public function attributeLabels() {
        return array(
            "comment" => "Название таксономии",
            "sprav_name" => "Название таблицы в БД",
            "task_id" => "Связанная задача"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "comment",
                "sprav_name"
            )
        );
    }

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
    public function getTableName() {
        return $this->sprav_name;
    }
    protected function getCacheTerms() {
        if (is_null($this->_cacheTerms)) {
            $this->_cacheTerms = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable($this->getTableName())->getItems() as $ar) {
                $term = new CTerm($ar);
                $term->taxonomy_id = $this->getId();
                $term->setTable($this->getTableName());
                $this->_cacheTerms->add($term->getId(), $term);
            }
        }
        return $this->_cacheTerms;
    }

    /**
     * На самом деле, это название таблицы, из которой
     * берутся термины. Переопределил для идентичности
     *
     * @return mixed|null|string
     */
    public function getAlias() {
        return $this->getTableName();
    }
}