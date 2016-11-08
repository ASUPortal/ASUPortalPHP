<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 06.02.15
 * Time: 17:40
 */

class CPrintClassFieldToFieldAdapter extends CPrintField{
    private $_classField = null;
    private $_context = null;
    public $children = null;
    public $parent = null;
    public $title = null;
    public $alias = null;

    public function __construct(IPrintClassField $classField, $context) {
        $this->_classField = $classField;
        $this->_context = $context;
        /**
         * В CPrintField эти члены публичные, их значения надо заполнить уже в конструкторе
         */
        $this->children = new CArrayList();
        if (!is_null($classField)) {
            if ($this->getClassField()->getFieldType() == IPrintClassField::FIELD_TABLE) {
                $this->type_id = "2";
            } else {
                $this->type_id = "1";
            }
            $this->parent = $this->getClassField()->getParentClassField();
            $this->title = $this->getClassField()->getFieldName();
            $this->alias = get_class($classField).".class";
        }
    }

    /**
     * @return IPrintClassField
     */
    private function getClassField() {
        return $this->_classField;
    }

    private function getContext() {
        return $this->_context;
    }

    /**
     * Делегируем вычисление классу-поля
     *
     * @param $object
     * @return mixed
     */
    public function evaluateValue($object) {
        return $this->getClassField()->execute($this->getContext());
    }


}