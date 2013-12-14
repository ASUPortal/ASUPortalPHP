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
    private static $_cacheModelValidators = null;
    private static $_cacheModelFieldTranslations = null;
    private static $_cacheModelFieldValidators = null;
    private static $_cacheValidators = null;
    private static $_cacheModelTasks = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheModelTasks() {
        if (is_null(self::$_cacheModelTasks)) {
            self::$_cacheModelTasks = new CArrayList();
        }
        return self::$_cacheModelTasks;
    }

    /**
     * @return CArrayList
     */
    private static function getCacheModelValidators() {
        if (is_null(self::$_cacheModelValidators)) {
            self::$_cacheModelValidators = new CArrayList();
        }
        return self::$_cacheModelValidators;
    }

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
     * @return CArrayList|null
     */
    private static function getCacheValidators() {
        if (is_null(self::$_cacheValidators)) {
            self::$_cacheValidators = new CArrayList();
        }
        return self::$_cacheValidators;
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheModelFieldValidators() {
        if (is_null(self::$_cacheModelFieldValidators)) {
            self::$_cacheModelFieldValidators = new CArrayList();
        }
        return self::$_cacheModelFieldValidators;
    }

    /**
     * @param $key
     * @return CCoreModel
     */
    public static function getCoreModel($key) {
        if (is_object($key)) {
            $key = get_class($key);
        }
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
                $ar = CActiveRecordProvider::getById(TABLE_CORE_MODEL_FIELD_TRANSLATIONS, $key);
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

    /**
     * Получаем валидаторы из модели
     *
     * @param CModel $model
     * @return CArrayList
     */
    public static function getModelValidators(CModel $model) {
        $validators = new CArrayList();
        $coreModel = self::getCoreModel(get_class($model));
        if (!is_null($coreModel)) {
            foreach ($coreModel->validators->getItems() as $validator) {
                if (!is_null($validator->validator)) {
                    $val = $validator->validator;
                    $class_name = $val->class_name;
                    $obj = new $class_name();
                    $validators->add($validators->getCount(), $obj);
                }
            }
        }
        return $validators;
    }

    /**
     * Валидаторы полей для модели
     *
     * @param CModel $model
     * @return array
     */
    public static function getFieldValidators(CModel $model) {
        $validators = array();
        /**
         * Сначала получаем валидаторы из модели
         */
        foreach ($model->getValidationRules() as $type=>$fields) {
            foreach ($fields as $field) {
                $v = array();
                if (array_key_exists($field, $validators)) {
                    $v = $validators[$field];
                }
                $v[] = $type;
                $validators[$field] = $v;
            }
        }
        /**
         * Теперь берем валидаторы из базы
         */
        $persistedModel = CCoreObjectsManager::getCoreModel(get_class($model));
        if (!is_null($persistedModel)) {
            foreach ($persistedModel->fields->getItems() as $field) {
                foreach ($field->validators->getItems() as $validator) {
                    $v = array();
                    if (array_key_exists($field->field_name, $validators)) {
                        $v = $validators[$field->field_name];
                    }
                    if (!is_null($validator->getValidatorObject())) {
                        $v[] = $validator->getValidatorObject();
                    }
                    $validators[$field->field_name] = $v;
                }
            }
        }
        return $validators;
    }

    /**
     * @param $key
     * @return CCoreValidator
     */
    public static function getCoreValidator($key) {
        if (!self::getCacheValidators()->hasElement($key)) {
            $ar = null;
            if (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_CORE_VALIDATORS, $key);
            }
            if (!is_null($ar)) {
                $validator = new CCoreValidator($ar);
                self::getCacheValidators()->add($validator->getId(), $validator);
            }
        }
        return self::getCacheValidators()->getItem($key);
    }

    /**
     * @param $key
     * @return CCoreModelFieldValidator
     */
    public static function getCoreModelFieldValidator($key) {
        if (!self::getCacheModelFieldValidators()->hasElement($key)) {
            $ar = null;
            if (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_CORE_MODEL_FIELD_VALIDATORS, $key);
            }
            if (!is_null($ar)) {
                $validator = new CCoreModelFieldValidator($ar);
                self::getCacheModelFieldValidators()->add($validator->getId(), $validator);
            }
        }
        return self::getCacheModelFieldValidators()->getItem($key);
    }

    /**
     * @return array
     */
    public static function getCoreValidatorsList($type) {
        $res = array();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_CORE_VALIDATORS, "type_id = ".$type)->getItems() as $ar) {
            $validator = new CCoreValidator($ar);
            self::getCacheValidators()->add($validator->getId(), $validator);
            $res[$validator->getId()] = $validator->title;
        }
        return $res;
    }

    /**
     * @param $key
     * @return CCoreModelValidator
     */
    public static function getCoreModelValidator($key) {
        if (!self::getCacheModelValidators()->hasElement($key)) {
            $ar = null;
            if (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_CORE_MODEL_VALIDATORS, $key);
            }
            if (!is_null($ar)) {
                $validator = new CCoreModelValidator($ar);
                self::getCacheModelValidators()->add($validator->getId(), $validator);
            }
        }
        return self::getCacheModelValidators()->getItem($key);
    }

    /**
     * @param $key
     * @return CCoreModelTask
     */
    public static function getCoreModelTask($key) {
        if (!self::getCacheModelTasks()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_CORE_MODEL_TASKS, $key);
            if (!is_null($ar)) {
                $task = new CCoreModelTask($ar);
                self::getCacheModelTasks()->add($task->getId(), $task);
            }
        }
        return self::getCacheModelTasks()->getItem($key);
    }

    /**
     * @return CArrayList
     */
    public static function getAllExportableModels() {
        $result = new CArrayList();
        foreach (CActiveRecordProvider::getAllFromTable(TABLE_CORE_MODELS)->getItems() as $ar) {
            $model = new CCoreModel($ar);
            if ($model->isExportable()) {
                $result->add($model->getId(), $model);
            }
        }
        return $result;
    }

    /**
     * Все модели, которые связаны с указанной задачей
     *
     * @param $key
     * @return CArrayList
     */
    public static function getModelsByTask($key) {
        $result = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_CORE_MODEL_TASKS, "task_id=".$key)->getItems() as $ar) {
            $modelTask = new CCoreModelTask($ar);
            if (!is_null($modelTask->model)) {
                $model = $modelTask->model;
                $result->add($model->getId(), $model);
            }
        }
        return $result;
    }
}