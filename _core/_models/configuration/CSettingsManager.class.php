<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.01.13
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */
define("CACHE_APPLICATION_SETTINGS", "application_settings");

class CSettingsManager {
    private static $_cacheSettings = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheSettings() {
        if (is_null(self::$_cacheSettings)) {
            self::$_cacheSettings = new CArrayList();
            if (CSettingsManager::getSettingValue("preload_settings")) {
                // будет с поддержкой кеша
                if (is_null(CApp::getApp()->cache->get(CACHE_APPLICATION_SETTINGS))) {
                    foreach (CActiveRecordProvider::getAllFromTable(TABLE_SETTINGS)->getItems() as $item) {
                        $setting = new CSetting($item);
                        self::getCacheSettings()->add($setting->getId(), $setting);
                        self::getCacheSettings()->add(strtoupper($setting->alias), $setting);
                    }
                    CApp::getApp()->cache->set(CACHE_APPLICATION_SETTINGS, self::$_cacheSettings, 3600);
                } else {
                    self::$_cacheSettings = CApp::getApp()->cache->get(CACHE_APPLICATION_SETTINGS);
                }
            }
        }
        return self::$_cacheSettings;
    }

    /**
     * Получить настройку по псевдониму или ключевому полю
     *
     * @param $key
     * @return CSetting
     */
    public static function getSetting($key) {
        if (is_string($key)) {
            $key = strtoupper($key);
        }
        if (!self::getCacheSettings()->hasElement($key)) {
            if (is_string($key)) {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_SETTINGS, "UPPER(alias) = '".$key."'")->getItems() as $item) {
                    $setting = new CSetting($item);
                    self::getCacheSettings()->add($setting->getId(), $setting);
                    self::getCacheSettings()->add($key, $setting);
                }
            } elseif (is_numeric($key)) {
                $item = CActiveRecordProvider::getById(TABLE_SETTINGS, $key);
                if (!is_null($item)) {
                    $setting = new CSetting($item);
                    self::getCacheSettings()->add(strtoupper($setting->alias), $setting);
                    self::getCacheSettings()->add($key, $setting);
                }
            }
        }
        return self::getCacheSettings()->getItem($key);
    }

    /**
     * Получить значение параметра
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
}
