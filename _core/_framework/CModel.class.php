<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 04.05.12
 * Time: 22:17
 * To change this template use File | Settings | File Templates.
 */
class CModel {
    private $_errors = null;
    private static $_thisObject = null;
    /**
     * Название класса
     *
     * @static
     * @return string
     */
    public static function getClassName() {
        return __CLASS__;
    }
    /**
     * Переопределяемый метод для указания отношений между сущностями
     * @return array
     */
    protected function relations() {
        return array();
    }
    /**
     * Переопределяемый метод для указания названий полей в сущности
     *
     * @return array
     */
    public function attributeLabels() {
        return array();
    }
    /**
     * Автоматическая установка всех полей в записи из запроса
     *
     * @param array $array
     */
    public function setAttributes(array $array) {
        // загрузка файлов из загружаемых полей
        foreach ($this->fieldsProperty() as $field=>$property) {
            if ($property['type'] == FIELD_UPLOADABLE) {
                if (array_key_exists($this::getClassName(), $_FILES)) {
                    if ($_FILES[$this::getClassName()]['tmp_name'][$field] !== "") {
                        $fileName = CUtils::toTranslit($_FILES[$this::getClassName()]['name'][$field]);
                        $fileName = date("dmY_His")."_".$fileName;
                        /**
                         * Проверяем, что upload_dir существует
                         * Если его нет, то создадим
                         */
                        $uploadDir = $property["upload_dir"];
                        $uploadDir = explode(CORE_DS, $uploadDir);
                        $checkPath = CORE_DS;
                        foreach ($uploadDir as $path) {
                            if ($path !== "") {
                                $checkPath .= $path.CORE_DS;
                                if (!file_exists($checkPath)) {
                                    mkdir($checkPath, 0777);
                                }
                            }
                        }
                        while (file_exists($property['upload_dir'].$fileName)) {
                            $fileName = date("dmY_His")."_".$fileName;
                        }
                        move_uploaded_file($_FILES[$this::getClassName()]['tmp_name'][$field], $property['upload_dir'].$fileName);
                        $array[$field] = $fileName;
                    }
                }
            }
        }
        foreach ($array as $key=>$value) {
            $this->$key = $value;
        }
    }
    /**
     * Правила валидации модели, например, проверки на обязательные поля
     *
     * @return array
     */
    protected function validationRules() {
        return array();
    }
    /**
     * Правила валидации для работы с CHtml
     * @return arrray
     */
    public function getValidationRules() {
	    return $this->validationRules();
    }
    /**
     * Функция валидации данных модели
     *
     * @return bool
     */
    public function validate() {
        $rules = $this->validationRules();

        if (array_key_exists("required", $rules)) {
            $required = $rules["required"];
            foreach($required as $field) {
                $error = str_replace("%name%", $this->getAttributeLabel($field), ERROR_FIELD_REQUIRED);
                if (is_null($this->$field)) {
                    $this->getValidationErrors()->add($field, $error);
                } elseif ($this->$field == "") {
                    $this->getValidationErrors()->add($field, $error);
                }
            }
        }
        if (array_key_exists("numeric", $rules)) {
            $numeric = $rules["numeric"];
            foreach($numeric as $field) {
                $error = str_replace("%name%", $this->getAttributeLabel($field), ERROR_FIELD_NUMERIC);
                if (!is_numeric($this->$field)) {
                    $this->getValidationErrors()->add($field, $error);
                }
            }
        }
        if (array_key_exists("selected", $rules)) {
            $selected = $rules["selected"];
            foreach ($selected as $field) {
                $error = str_replace("%name%", $this->getAttributeLabel($field), ERROR_FIELD_SELECTED);
                if ($this->$field == 0) {
                    $this->getValidationErrors()->add($field, $error);
                }
            }
        }
        if (array_key_exists("checkdate", $rules)) {
            $dates = $rules["checkdate"];
            foreach ($dates as $field) {
                $error = str_replace("%name%", $this->getAttributeLabel($field), ERROR_FIELD_NOT_A_DATE);
                if ($this->$field != "") {
                    $dateValue = $this->$field;
                    if (strtotime($dateValue) === false) {
                        $this->getValidationErrors()->add($field, $error);
                    } else {
                        $dateArray = explode(".", $dateValue);
                        if (!checkdate($dateArray[1], $dateValue[0], $dateArray[2])) {
                            $this->getValidationErrors()->add($field, $error);
                        }
                    }
                }
            }
        }

        return $this->getValidationErrors()->getCount() == 0;
    }
    /**
     * Пееропределение связи свойств объекта с полями таблицы БД.
     * На случай, если имена полей в таблице давали нерусские люди
     *
     * @return array
     */
    protected function fieldsMapping() {
        return array(

        );
    }

    /**
     * Свойства полей, например, загрузка файлов
     *
     * @return array
     */
    protected function fieldsProperty() {
        return array(

        );
    }
    /**
     * Лист ошибок валидации модели
     *
     * @return CArrayList
     */
    public function getValidationErrors() {
        if (is_null($this->_errors)) {
            $this->_errors = new CArrayList();
        }
        return $this->_errors;
    }
    /**
     * Название атрибута модели
     *
     * @param $name
     * @return string
     */
    public function getAttributeLabel($name) {
        $labels = $this->attributeLabels();
        if (array_key_exists($name, $labels)) {
            return $labels[$name];
        } else {
            return $name;
        }
    }
}
