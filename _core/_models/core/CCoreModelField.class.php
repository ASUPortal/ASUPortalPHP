<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 14:35
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelField extends CActiveModel{
    protected $_table = TABLE_CORE_MODEL_FIELDS;
    protected $_translations = null;
    protected $_validators = null;
    protected $_model = null;

    public $export_to_search = 0;
    public $model_id;
    public $is_readers = 0;
    public $is_authors = 0;

    /**
     * Поддерживаем ли модель выгрузку в поиск
     *
     * @return bool
     */
    public function isExportable() {
        return ($this->export_to_search == "1");
    }

    /**
     * Лимитирует ли поле доступ на чтение
     *
     * @return bool
     */
    public function isReaders() {
        return ($this->is_readers == "1");
    }

    /**
     * Лимитирует ли поле доступ на запись
     *
     * @return bool
     */
    public function isAuthors() {
        return ($this->is_authors == "1");
    }

    public function relations() {
        return array(
            "translations" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_translations",
                "storageTable" => TABLE_CORE_MODEL_FIELD_TRANSLATIONS,
                "storageCondition" => "field_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelFieldTranslation"
            ),
            "model" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_model",
                "storageField" => "model_id",
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModel"
            ),
            "validators" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_validators",
                "storageTable" => TABLE_CORE_MODEL_FIELD_VALIDATORS,
                "storageCondition" => "field_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelFieldValidator"
            )
        );
    }

    /**
     * Получить значение перевода для указанного языка
     *
     * @param $id
     * @return string
     */
    public function getTranslationByLangId($id) {
        $value = "";
        foreach ($this->translations->getItems() as $t) {
            if ($t->language_id = $id) {
                $value = $t->value;
            }
        }
        return $value;
    }
    /**
     * Получить значение для столбца заголовков таблицы 
     * @param $id
     * @return string
     */
    public function getTranslationTableByLangId($id) {
    	$value = "";
    	foreach ($this->translations->getItems() as $t) {
    		if ($t->language_id = $id) {
    			$value = $t->table_value; 
    		}
    	}
    	return $value;
    }
    
    /**
     * Перевод на текущий язык
     *
     * @return string
     */
    public function getTranslationDefault() {
        return $this->getTranslationByLangId(CSettingsManager::getSettingValue("system_language_default"));
    }
    /**
     * Перевод столбца таблицы на текущий язык
     * @return string
     */
    public function getTranslationTableDefault() {
    	return $this->getTranslationTableByLangId(CSettingsManager::getSettingValue("system_language_default"));
    }
}