<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 21.11.12
 * Time: 20:46
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class CPrintFormset
 *
 * @property CArrayList fields
 */
class CPrintFormset extends CActiveModel{
    protected $_table = TABLE_PRINT_FORMSETS;
    protected $_forms = null;
    protected $_fields = null;
    protected $_activeForms = null;

    public function attributeLabels() {
        return array(
            "title" => "Название",
            "alias" => "Псевдоним набора",
            "description" => "Описание",
            "context_evaluate" => "Настройки контекста",
            "context_variables" => "Переменные контекста"
        );
    }

    public static function getClassName() {
        return __CLASS__;
    }
    protected function relations() {
    	return array(
            "forms" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_forms",
                "storageTable" => TABLE_PRINT_FORMS,
                "storageCondition" => "formset_id = " . $this->id,
                "managerClass" => "CPrintManager",
                "managerGetObject" => "getForm"
            ),
            "fields" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_fields",
                "storageTable" => TABLE_PRINT_FIELDS,
                "storageCondition" => "formset_id = " . $this->id,
                "managerClass" => "CPrintManager",
                "managerGetObject" => "getField"
            ),
            "activeForms" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_activeForms",
                "storageTable" => TABLE_PRINT_FORMS,
                "storageCondition" => "isActive = 1 and formset_id = " . $this->id,
                "managerClass" => "CPrintManager",
                "managerGetObject" => "getForm"
            ),
    	);
    }  
    /**
     * 
     * @return multitype:
     */
    public function computeTemplateVariables() {
    	/**
    	 * Для нормальной работы нам надо вычислить переменные:
    	 * 1. Класс менеджера
    	 * 2. Метод менеджера по получению нужно объекта
    	 * 3. Идентификатор объекта для получения
    	 */
    	$res = array();
    	if ($this->context_evaluate !== "") {
    		eval('$res = '.$this->context_evaluate.';');
    	}
    	return $res;
    }

    /**
     * Получить поле по имени
     *
     * @param $name
     * @return CPrintField
     */
    public function getFieldByName($name) {
        $res = null;
        foreach ($this->fields->getItems() as $field) {
            if ($field->alias == $name) {
                $res = $field;
            }
        }
        return $res;
    }
}
