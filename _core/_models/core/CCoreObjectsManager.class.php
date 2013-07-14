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
    private static $_cacheModelFields = null;
    private static $_cacheModelFieldTranslations = null;

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
     * @return CArrayList|null
     */
    private static function getCacheModelFields() {
        if (is_null(self::$_cacheModelFields)) {
            self::$_cacheModelFields = new CArrayList();
        }
        return self::$_cacheModelFields;
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheModelFieldTranslations() {
        if (is_null(self::$_cacheModelFieldTranslations)) {
            self::$_cacheModelFieldTranslations = new CArrayList();
        }
        return self::$_cacheModelFieldTranslations;
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
            } elseif (is_string($key)) {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_CORE_MODELS, "class_name='".$key."'")->getItems() as $a) {
                    $ar = $a;
                }
            }
            if (!is_null($ar)) {
                $model = new CCoreModel($ar);
                self::getCacheModels()->add($model->getId(), $model);
                self::getCacheModels()->add($model->class_name, $model);
            }
        }
        return self::getCacheModels()->getItem($key);
    }

    /**
     * @param $key
     * @return CCoreModelField
     */
    public static function getCoreModelField($key) {
        if (!self::getCacheModelFields()->hasElement($key)) {
            $ar = null;
            if (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_CORE_MODEL_FIELDS, $key);
            }
            if (!is_null($ar)) {
                $field = new CCoreModelField($ar);
                self::getCacheModelFields()->add($field->getId(), $field);
            }
        }
        return self::getCacheModelFields()->getItem($key);
    }

    /**
     * @param $key
     * @return CCoreModelFieldTranslation
     */
    public static function getCoreModelFieldTranslation($key) {
        if (!self::getCacheModelFieldTranslations()->hasElement($key)) {
            $ar = null;
            if (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_CORE_MODLE_FIELD_TRANSLATIONS, $key);
            }
            if (!is_null($ar)) {
                $t = new CCoreModelFieldTranslation($ar);
                self::getCacheModelFieldTranslations()->add($t->getId(), $t);
            }
        }
        return self::getCacheModelFieldTranslations()->getItem($key);
    }
    public static function getAttributeLabels(CModel $model) {
        $translation = array();
        /**
         * Получаем перевод из метода getAttributeLabels
         */
        foreach ($model->attributeLabels() as $key=>$value) {
            $translation[$key] = $value;
        }
        /**
         * Получаем модель-описание для текущей модели
         */
        $descriptionModel = self::getCoreModel(get_class($model));
        if (!is_null($descriptionModel)) {
            /**
             * Получаем все поля и переводы для них
             * для языка системы по умолчанию
             */
            $tr = $descriptionModel->getTranslationDefault();
            foreach ($tr as $key=>$value) {
                $translation[$key] = $value;
            }
            /**
             * Теперь для текущего языка
             */
            $tr = $descriptionModel->getTranslationCurrent();
            foreach ($tr as $key=>$value) {
                $translation[$key] = $value;
            }
        }
        return $translation;
    }
}