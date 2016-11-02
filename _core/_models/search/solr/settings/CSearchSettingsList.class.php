<?php
/**
 * Модель для настроек поиска по элементу настройки индекса Solr
 * 
 * class CSearchSettingsList
 */
class CSearchSettingsList extends CActiveModel {
    protected $_table = TABLE_SETTINGS_SOLR_SEARCH;
    
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "alias" => "Ключ",
            "value" => "Значение",
            "description" => "Описание"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title",
                "alias",
                "value"
            )
        );
    }

    /**
     * Получить значение настройки
     *
     * @return mixed|null
     */
    public function getValue() {
		return $this->value;
    }
    
    /**
     * Получить псевдоним настройки
     *
     * @return mixed|null
     */
    public function getAlias() {
		return $this->alias;
    }
}
