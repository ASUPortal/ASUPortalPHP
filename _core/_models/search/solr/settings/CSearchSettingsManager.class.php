<?php

/**
 * Менеджер настроек поиска по индексу Solr
 * 
 * class CSearchSettingsManager
 */
class CSearchSettingsManager {
    /**
     * Получить настройку коллекции Solr по псевдониму или ключевому полю
     *
     * @param $key
     * @return CSearchSettings
     */
    public static function getSetting($key) {
        if (is_string($key)) {
            $key = strtoupper($key);
        }
        if (is_string($key)) {
        	foreach (CActiveRecordProvider::getWithCondition(TABLE_SETTINGS_SOLR_CORES, "UPPER(alias) = '".$key."'")->getItems() as $item) {
        		$setting = new CSearchSettings($item);
        	}
        } elseif (is_numeric($key)) {
        	$item = CActiveRecordProvider::getById(TABLE_SETTINGS_SOLR_CORES, $key);
        	if (!is_null($item)) {
        		$setting = new CSearchSettings($item);
        	}
        }
        return $setting;
    }

    /**
     * Получить значение параметра коллекции Solr
     *
     * @param $key
     * @return string
     */
    public static function getSettingValue($key) {
        if (is_null(self::getSetting($key))) {
            return "";
        }
        return self::getSetting($key)->getValue();
    }
    
    /**
     * Получить элемент настройки Solr по псевдониму или ключевому полю
     *
     * @param $key
     * @return CSearchSettingsList
     */
    public static function getSettingItem($key) {
    	if (is_string($key)) {
    		$key = strtoupper($key);
    	}
    	if (is_string($key)) {
            foreach (CActiveRecordProvider::getWithCondition(TABLE_SETTINGS_SOLR_SEARCH, "UPPER(alias) = '".$key."'")->getItems() as $item) {
        		$setting = new CSearchSettingsList($item);
            }
    	} elseif (is_numeric($key)) {
            $item = CActiveRecordProvider::getById(TABLE_SETTINGS_SOLR_SEARCH, $key);
            if (!is_null($item)) {
        		$setting = new CSearchSettingsList($item);
            }
    	}
    	return $setting;
    }
    
    /**
     * Получить значение параметра элемента настройки Solr
     *
     * @param $key
     * @return string
     */
    public static function getSettingValueItem($key) {
    	if (is_null(self::getSettingItem($key))) {
    		return "";
    	}
    	return self::getSettingItem($key)->getValue();
    }
}
