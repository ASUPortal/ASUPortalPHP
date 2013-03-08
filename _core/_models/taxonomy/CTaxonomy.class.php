<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 08.05.12
 * Time: 22:41
 * To change this template use File | Settings | File Templates.
 *
 * Словарь таксономии
 */
class CTaxonomy extends CActiveModel {
    protected $_table = TABLE_TAXONOMY;
    private $_cacheTerms = null;
    /**
     * Кэш терминов словаря
     *
     * @return CArrayList
     */
    private function getCacheTerms() {
        if (is_null($this->_cacheTerms)) {
            $this->_cacheTerms = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_TAXONOMY_TERMS, "taxonomy_id=".$this->getId())->getItems() as $item) {
                $term = new CTerm($item);
                $this->_cacheTerms->add($term->getId(), $term);
            }
        }
        return $this->_cacheTerms;
    }
    /**
     * Псевдоним названия таксономии
     *
     * @return string
     */
    public function getAlias() {
        return $this->getRecord()->getItemValue("alias");
    }
    /**
     * Список терминов таксономии для выпадающих полей
     *
     * @return array
     */
    public function getTermsList() {
        $res = array();
        foreach ($this->getCacheTerms()->getItems() as $item) {
            $res[$item->getId()] = $item->getValue();
        }
        return $res;
    }
    /**
     * Лист терминов таксономии
     *
     * @return CArrayList
     */
    public function getTerms() {
        return $this->getCacheTerms();
    }
    /**
     * Добавляет термин в таксономию
     *
     * @param CTerm $term
     */
    public function addTerm(CTerm $term) {
        if (is_null($this->_cacheTerms)) {
            $this->_cacheTerms = new CArrayList();
        }
        $this->_cacheTerms->add($term->getId(), $term);
        $this->_cacheTerms->add(mb_strtoupper($term->getValue()), $term);
    }
    /**
     * Инициализация массива терминов пустым массивом.
     * Нужно для полной инициализации терминов
     */
    public function initTerms() {
        if (is_null($this->_cacheTerms)) {
            $this->_cacheTerms = new CArrayList();
        }
    }
    /**
     * Название таксономии
     *
     * @return string
     */
    public function getName() {
        return $this->getRecord()->getItemValue("name");
    }
    public function save() {
        parent::save();

        if (!CTaxonomyManager::getCacheTaxonomy()->hasElement($this->getId())) {
            CTaxonomyManager::getCacheTaxonomy()->add($this->getId(), $this);
            CTaxonomyManager::getCacheTaxonomy()->add($this->getAlias(), $this);
        }
    }

    /**
     * Термин по названию, идентификатору или псевдониму
     *
     * @param $key
     * @return CTerm
     */
    public function getTerm($key) {
        $term = null;
        if (is_numeric($key)) {
            $term = $this->getCacheTerms()->getItem($key);
        } elseif (is_string($key)) {
            $key = mb_strtoupper($key);
            if (!$this->getCacheTerms()->hasElement($key)) {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_TAXONOMY_TERMS, "UPPER(name) = '".$key."'")->getItems() as $item) {
                    $term = new CTerm($item);
                    $this->getCacheTerms()->add($term->getId(), $term);
                    $this->getCacheTerms()->add($key, $term);
                }
            } else {
                $term = $this->getCacheTerms()->getItem($key);
            }
        }
        return $term;
    }
}
