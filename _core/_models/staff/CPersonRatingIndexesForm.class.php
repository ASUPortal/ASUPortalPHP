<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 24.09.12
 * Time: 22:45
 * To change this template use File | Settings | File Templates.
 */
class CPersonRatingIndexesForm extends CFormModel {
    private $_indexes = null;
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "person_id" => "Сотрудник",
            "indexes" => "Показатели",
            "year_id" => "Год"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "person_id"
            )
        );
    }
    /**
     * Показатели, переданные в форме
     *
     * @return CArrayList
     */
    public function getIndexes() {
        if (is_null($this->_indexes)) {
            $this->_indexes = new CArrayList();
            foreach ($this->getItems()->getItems() as $key=>$item) {
                if (strpos($key, "indexes") !== false) {
                    foreach ($item as $value) {
                        $indexValue = CRatingManager::getRatingIndexValue($value);
                        if (!is_null($indexValue)) {
                            $this->_indexes->add($indexValue->getId(), $indexValue);
                        }
                    }
                }
            }
        }
        return $this->_indexes;
    }
}
