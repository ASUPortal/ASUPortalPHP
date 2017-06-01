<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 21.11.12
 * Time: 23:08
 * To change this template use File | Settings | File Templates.
 */
class CPrintField extends CActiveModel {
    protected $_table = TABLE_PRINT_FIELDS;
    /**
     * Публичные свойства
     */
    public $title;
    public $alias;
    public $description;
    public $value_evaluate;
    public $parent_node;
    public $formset_id;
    public $parent_id;
    public $type_id;
    /**
     * Свойства для хранения связей
     */
    protected $_formset = null;
    protected $_children = null;
    protected $_parent = null;

    protected function relations() {
        return array(
            "formset" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_formset",
                "storageField" => "formset_id",
                "managerClass" => "CPrintManager",
                "managerGetObject" => "getFormset"
            ),
            "children" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_children",
                "storageTable" => TABLE_PRINT_FIELDS,
                "storageCondition" => "parent_id = " . $this->id,
                "managerClass" => "CPrintManager",
                "managerGetObject" => "getField"
            ),
            "parent" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_parent",
                "storageField" => "parent_id",
                "managerClass" => "CPrintManager",
                "managerGetObject" => "getField"
            )
        );
    }
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "alias" => "Описатель в документе",
            "description" => "Описание",
            "parent_id" => "Родительский описатель",
            "formset_id" => "Набор форм",
            "value_evaluate" => "Код описателя",
            "type_id" => "Тип описателя",
            "parent_node" => "DOM-узел, который содержит дочерний шаблон"
        );
    }

    /**
     * Вычислить значение описателя поля на основе переданного объекта;
     * Параметр $stripTags: уберём HTML-теги и преобразуем HTML-сущности в соответствующие символы
     *
     * @param $object
     * @param $stripTags
     * @return string|array|CArrayList
     */
    public function evaluateValue($object, $stripTags = false) {
        /**
         * На случай, если это дочерний описатель
         * переопределяем значения
         */
        $isChild = false;
        if (!is_null($this->parent)) {
            $item = $object;
            $isChild = true;
        }
        if ($this->children->getCount() > 0) {
            $value = new CArrayList();
            eval($this->value_evaluate);
            return $value;
        } elseif ($this->type_id == "1" || $this->type_id == "0") {
            if ($isChild) {
                $childValue = "";
                eval($this->value_evaluate);
                return $childValue;
            } else {
                $value = "";
                eval($this->value_evaluate);
                if (!$stripTags) {
                	return $value;
                } else {
                	return html_entity_decode(strip_tags($value));
                }
            }
        } elseif ($this->type_id == "2") {
            if ($isChild) {
                $childValue = array();
                eval($this->value_evaluate);
                if (is_string($childValue)) {
                    $childValue = array(
                        array(
                            $childValue
                        )
                    );
                }
                return $childValue;
            } else {
                $value = array();
                eval($this->value_evaluate);
                if (is_string($value)) {
                    $value = array(
                        array(
                            $value
                        )
                    );
                }
                return $value;
            }
        }
    }
    
    /**
     * @return String
     */
    public function getFieldType() {
        if ($this->type_id == "1" || $this->type_id == "0") {
            return IPrintClassField::FIELD_TEXT;
        } elseif ($this->type_id == "2") {
            return IPrintClassField::FIELD_TABLE;
        } else {
            throw new Exception("Unsupported field type " . $this->type_id);
        }
    }
    
    public function getColSpan() {
        return "1";
    }
    
    public function getRowSpan() {
        return "1";
    }
}
