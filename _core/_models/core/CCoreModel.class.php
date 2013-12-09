<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 14:01
 * To change this template use File | Settings | File Templates.
 */

class CCoreModel extends CActiveModel {
    protected $_table = TABLE_CORE_MODELS;
    protected $_fields = null;
    protected $_tasks = null;
    protected $_taskModels = null;
    protected $_readersFields = null;
    protected $_authorsFields = null;
    private $_model = null;
    public $export_to_search = 0;

    protected function relations() {
        return array(
            "fields" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_fields",
                "storageTable" => TABLE_CORE_MODEL_FIELDS,
                "storageCondition" => "model_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelField"
            ),
            "tasks" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_tasks",
                "joinTable" => TABLE_CORE_MODEL_TASKS,
                "leftCondition" => "model_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "task_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getUserRole"
            ),
            "taskModels" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_taskModels",
                "storageTable" => TABLE_CORE_MODEL_TASKS,
                "storageCondition" => "model_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelTask"
            ),
            "readersFields" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_readersFields",
                "storageTable" => TABLE_CORE_MODEL_FIELDS,
                "storageCondition" => "model_id = " . (is_null($this->getId()) ? 0 : $this->getId())." and is_readers = 1",
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelField"
            ),
            "authorsFields" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_authorsFields",
                "storageTable" => TABLE_CORE_MODEL_FIELDS,
                "storageCondition" => "model_id = " . (is_null($this->getId()) ? 0 : $this->getId())." and is_authors = 1",
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelField"
            )
        );
    }

    private function getTranslationByLangId($lang) {
        $translation = array();
        if ($lang !== "") {
            foreach ($this->fields->getItems() as $field) {
                if ($field->getTranslationByLangId($lang) !== "") {
                    $translation[$field->field_name] = $field->getTranslationByLangId($lang);
                }
            }
        }
        return $translation;
    }

    /**
     * Перевод полей модели на язык по умолчанию
     *
     * @return array
     */
    public function getTranslationDefault() {
        $translation = array();
        $lang = CSettingsManager::getSettingValue("system_language_default");
        $translation = $this->getTranslationByLangId($lang);
        return $translation;
    }

    /**
     * Перевод полей модели на текущий язык системы
     *
     * @return array
     */
    public function getTranslationCurrent() {
        $translation = array();
        $lang = CSettingsManager::getSettingValue("system_language_current");
        $translation = $this->getTranslationByLangId($lang);
        return $translation;
    }

    /**
     * Поддерживаем ли модель выгрузку в поиск
     *
     * @return bool
     */
    public function isExportable() {
        $exportable = false;
        foreach ($this->fields->getItems() as $field) {
            if ($field->isExportable()) {
                $exportable = true;
            }
        }
        return $exportable;
    }

    /**
     * @return CActiveModel
     */
    private function getModel() {
        if (is_null($this->_model)) {
            $class = $this->class_name;
            $this->_model = new $class();
        }
        return $this->_model;
    }

    /**
     * Название таблицы, в которой хранится модель
     *
     * @return string
     */
    public function getModelTable() {
        if (!is_null($this->getModel())) {
            return $this->getModel()->getTable();
        }
    }
}