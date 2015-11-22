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
     * Получить настройку по псевдониму или ключевому полю
     *
     * @param $key
     * @return CSetting
     */
    public static function getSetting($key) {
        if (is_string($key)) {
            $key = strtoupper($key);
        }
        $cacheKey = CACHE_APPLICATION_SETTINGS . '_' . $key;
        if (!CApp::getApp()->cache->hasCache($cacheKey)) {
            $found = false;
            if (is_string($key)) {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_SETTINGS, "UPPER(alias) = '".$key."'")->getItems() as $item) {
                    $found = true;
                    $setting = new CSetting($item);
                    CApp::getApp()->cache->set(CACHE_APPLICATION_SETTINGS . '_' .$setting->getId(), $setting);
                    CApp::getApp()->cache->set($cacheKey, $setting);
                }
            } elseif (is_numeric($key)) {
                $item = CActiveRecordProvider::getById(TABLE_SETTINGS, $key);
                if (!is_null($item)) {
                    $found = true;
                    $setting = new CSetting($item);
                    CApp::getApp()->cache->set(CACHE_APPLICATION_SETTINGS . '_' .$setting->alias, $setting);
                    CApp::getApp()->cache->set($cacheKey, $setting);
                }
            }
            if (!$found) {
                CApp::getApp()->cache->set($cacheKey, null);
            }
        }
        return CApp::getApp()->cache->get($cacheKey);
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
