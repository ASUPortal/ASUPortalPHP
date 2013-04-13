<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 08.05.12
 * Time: 21:11
 * To change this template use File | Settings | File Templates.
 *
 * Термин любого словаря, чтобы однообразный доступ к ним был
 */
class CTerm extends CActiveModel{
    protected $_table = TABLE_TAXONOMY_TERMS;
    private $_aRecord = null;
    private $_taxonomy = null;
    public static function getClassName() {
        return __CLASS__;
    }
    /**
     * Значение термина в зависимости от словаря, из которого
     * он взят
     *
     * @return string
     */
    public function getValue() {
        switch ($this->getTable()) {
        	case TABLE_MARKS:
        		return $this->getRecord()->getItemValue("name_short");
        		break;
            default:
                return $this->getRecord()->getItemValue("name");
            	break;
        }
    }
    /**
     * Объект таксономии, к которой данный термин привязан.
     * Если это самостоятельный объект словаря, то null
     *
     * @return CTaxonomy
     */
    public function getParentTaxonomy() {
        if (is_null($this->_taxonomy)) {
            if ($this->getRecord()->hasItem("taxonomy_id")) {
                $this->_taxonomy = CTaxonomyManager::getTaxonomy($this->getRecord()->getItemValue("taxonomy_id"));
            }
        }
        return $this->_taxonomy;
    }
    /**
     * Установка таксономии
     *
     * @param $taxonomy
     */
    public function setTaxonomy(CTaxonomy $taxonomy) {
        $this->_taxonomy = $taxonomy;
        $this->getRecord()->setItemValue("taxonomy_id", $taxonomy->getId());
    }
    /**
     * Значение термина
     *
     * @param $name
     */
    public function setValue($name) {
    	$this->getRecord()->setItemValue("name", $name);
    }
    /**
     * Значение псевдонима термина
     *
     * @param $alias
     */
    public function setAlias($alias) {
        $this->getRecord()->setItemValue("alias", $alias);
    }
    public function getAlias() {
        switch ($this->getTable()) {
            case TABLE_POSTS:
                return $this->getRecord()->getItemValue("name");
                break;
            case TABLE_TAXONOMY_TERMS:
                return $this->getRecord()->getItemValue("alias");
                break;
        }
    }
    /**
     * @return array
     */
    public function toArrayForJSON() {
        $r['id'] = $this->getId();
        $r['value'] = $this->getValue();
        return $r;
    }
    public function attributeLabels() {
        return array(
            "name" => "Значение",
            "alias" => "Псевдоним"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "name"
            )
        );
    }

    /**
     * Устанавливает таблицу. На случай, если
     * захочу сохранить термин в другой таблице
     *
     * @param $table
     * @return string|void
     */
    public function setTable($table) {
        $this->_table = $table;
        $this->getRecord()->setTable($table);
    }
}
