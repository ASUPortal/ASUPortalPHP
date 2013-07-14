<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 14:07
 * To change this template use File | Settings | File Templates.
 */

class CCoreObjectsManager {
    private static $_cacheModels = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheModels() {
        if (is_null(self::$_cacheModels)) {
            self::$_cacheModels = new CArrayList();
        }
        return self::$_cacheModels;
    }

    /**
     * @param $key
     * @return CCoreModel
     */
    public static function getCoreModel($key) {
        if (!self::getCacheModels()->hasElement($key)) {
            $ar = null;
            if (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_CORE_MODELS, $key);
            }
            if (!is_null($ar)) {
                $model = new CCoreModel($ar);
                self::getCacheModels()->add($model->getId(), $model);
            }
        }
        return self::getCacheModels()->getItem($key);
    }
}