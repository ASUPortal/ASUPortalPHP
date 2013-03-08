<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 29.09.12
 * Time: 18:23
 * To change this template use File | Settings | File Templates.
 */
class CRatingIndexValue extends CActiveModel {
    protected $_table = TABLE_RATING_INDEX_VALUES;
    protected $_parentIndex = null;
    protected $_persons = null;
    public function relations() {
        return array(
            "parentIndex" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_parentIndex",
                "storageField" => "index_id",
                "managerClass" => "CRatingManager",
                "managerGetObject" => "getRatingIndex"
            ),
            "persons" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_persons",
                "joinTable" => TABLE_PERSON_RATINGS,
                "leftCondition" => "index_id = ". $this->id,
                "rightKey" => "person_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            )
        );
    }
    /**
     * Из таксономии
     *
     * @return bool
     */
    public function isFromTaxonomy() {
        return ($this->fromTaxonomy == "1");
    }
    /**
     * Название значения показателя
     *
     * @return mixed
     */
    public function getTitle() {
        if ($this->isFromTaxonomy()) {
            if (!is_null($this->parentIndex)) {
                $manager = $this->parentIndex->manager_class;
                $method = $this->parentIndex->manager_method;

                if ($manager != "" && $method != "") {
                    $list = new CArrayList();
                    $str = '$list = '.$manager.'::'.$method.';';
                    eval($str);
                    $value = $list->getItem($this->title);
                    if (!is_null($value)) {
                        return $value->getValue();
                    }
                }
            }
        } else {
            return $this->title;
        }
    }
    /**
     * Значение показателя
     *
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }
}
