<?php
/**
 * Модель для настроек поиска по коллекции индекса Solr
 * 
 * class CSearchSettings
 */
class CSearchSettings extends CActiveModel {
    protected $_table = TABLE_SETTINGS_SOLR_CORES;
    
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
     * Список настроек коллекции Solr
     *
     * @return CArrayList
     */
    public function getSearchSettingsList() {
        $settings = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_SETTINGS_SOLR_SEARCH, "solr_core=".$this->getId(), "title asc")->getItems() as $ar) {
            $setting = new CSearchSettingsList($ar);
            $settings->add($setting->getId(), $setting);
        }
        return $settings;
    }
}
