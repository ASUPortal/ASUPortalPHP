<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 05.05.12
 * Time: 14:28
 * To change this template use File | Settings | File Templates.
 *
 * Первый уровень иерархии после CActiveRecord для доступа к данным
 */
class CActiveModel extends CModel implements IJSONSerializable{
    private $_aRecord = null;
    protected $_table = null;
    private $_dbTable = null;

    public function __construct(CActiveRecord $aRecord = null) {
        if (is_null($aRecord)) {
            $arr = array(
                "id" => null
            );

            $aRecord = new CActiveRecord($arr);
            $aRecord->setTable($this->_table);
        } else {
            /**
             * Заполняем публичные свойства объекта данными
             * из базы данных
             */
            foreach($aRecord->getItems() as $key=>$value) {
                if (property_exists(get_class($this), $key)) {
                    $this->$key = $value;
                }
            }
        }
        // если модель реализует интерфейс контроля версий, то
        // сразу заполняем ей некоторые поля
        if (is_a($this, "IVersionControl")) {
            $aRecord->setItemValue("_created_by", CSession::getCurrentPerson()->getId());
            $aRecord->setItemValue("_created_at", date('Y-m-d G:i:s'));
            $aRecord->setItemValue("_version_of", 0);
        }
        $this->_aRecord = $aRecord;
    }


    /**
     * Идентификатор записи в таблице
     *
     * @return int
     */
    public function getId() {
        if ($this->getRecord()->getId() !== "") {
            return $this->getRecord()->getId();
        } else {
            return $this->getRecord()->getItemValue("id");
        }
    }
    /**
     * Имя таблицы, к которой привязан объект
     *
     * @return string
     */
    protected function getTable() {
        if (is_null($this->_table)) {
            $this->_table = $this->getRecord()->getTable();
        }
        return $this->_table;
    }
    /**
     * Запись, на которой основана модель
     *
     * @return CActiveRecord
     */
    public function getRecord() {
        return $this->_aRecord;
    }
    /**
     * Сохранение или обновление данных модели
     */
    public function save() {
        /**
         * Из публичных свойств объекта пробуем получить
         * данные для записи на случай, если их не положил пользователь
         */
        foreach (get_object_vars($this) as $key=>$value) {
            if ($this->getDbTable()->getFields()->hasElement($key)) {
                $this->getRecord()->setItemValue($key, $value);
            }
        }
        if (is_null($this->getId()) | $this->getId() == "") {
            $this->saveModel();
            if (is_object(CApp::getApp()->getDbConnection())) {
                $this->setId(CApp::getApp()->getDbConnection()->lastInsertId());
            } else {
                $this->setId(mysql_insert_id());
            }
        } else {
            $this->updateModel();
        }
        // попытаемся сразу сохранить многие-ко-многим отношения
        foreach ($this->relations() as $field=>$relation) {
            if ($relation['relationPower'] == RELATION_MANY_TO_MANY) {
                // сохраним старое значение на всякий случай
                $currentValue = $this->$field;
                // удалим все старые
                foreach (CActiveRecordProvider::getWithCondition($relation['joinTable'], $relation['leftCondition'])->getItems() as $ar) {
                    $ar->remove();
                }
                // теперь сохраним новые
                foreach ($currentValue->getItems() as $key=>$value) {
                    $ar = new CActiveRecord(array(
                        CUtils::strLeft($relation['leftCondition'], " ") => $this->getId(),
                        $relation['rightKey'] => $key,
                        "id" => null
                    ));
                    $ar->setTable($relation['joinTable']);
                    $ar->insert();
                }
            }
        }
    }
    /**
     * Сохранение новой модели
     */
    private function saveModel() {
        $this->getRecord()->insert();
    }
    /**
     * Обновление существующей модели
     */
    private function updateModel() {
        // если эта модель поддерживает версионирование,
        // то сначала делаем копию текущей записи, а затем
        // сохраняем данные
        if (is_a($this, "IVersionControl")) {
            $currentAr = CActiveRecordProvider::getById($this->getTable(), $this->getId());
            $currentAr->setItemValue("_version_of", $this->getId());
            $currentAr->setItemValue("_created_at", date('Y-m-d G:i:s'));
            $currentAr->setItemValue("_created_by", CSession::getCurrentPerson()->getId());
            $currentAr->insert();
        }
        $this->getRecord()->update();
    }
    /**
     * Установить значение id
     *
     * @param $id
     */
    public function setId($id) {
        $this->getRecord()->setItemValue($this->getRecord()->getPk(), $id);
    }

    /**
     * Установить название первичного ключа
     *
     * @param $key
     */
    public function setPk($key) {
        $record = $this->getRecord();
        $record::setPk($key);
    }
    /**
     * Удаление текущей записи из БД
     */
    public function remove() {
        /**
         * Перед удалением объекта выполним валидаторы onDelete для модели
         */
        $valid = $this->validateModel(VALIDATION_EVENT_REMOVE);
        if (!$valid) {
            $controller = CSession::getCurrentController();
            if (!is_null($controller)) {
                $url = WEB_ROOT;
                if (array_key_exists("HTTP_REFERER", $_SERVER)) {
                    $url = $_SERVER["HTTP_REFERER"];
                }
                $controller->redirect($url, $this->getValidationErrors());
            }
        }

        $this->getRecord()->remove();
    }
    /**
     * Волшебный сеттер
     * @param type $name
     * @param type $value
     */
    public function __set($name, $value) {
        // на случай наличия маппинга поля
        $mapping = $this->fieldsMapping();
        if (array_key_exists($name, $mapping)) {
            $name = $mapping[$name];
        }
        // сначала проверим какого типа пришло значение
        // просто setItemValue() вызываем только в случае
        // простых типов данных
        if (!is_object($value)) {
            if ($this->getDbTable()->getFields()->hasElement($name)) {
                $this->getRecord()->setItemValue($name, $value);
                if (!array_key_exists($name, $this->getRecord()->getItems())) {
                    $trace = debug_backtrace();
                    trigger_error(
                        'Неопределенное свойство в __set(): ' . $name .
                            ' в файле ' . $trace[0]['file'] .
                            ' на строке ' . $trace[0]['line'],
                        E_USER_NOTICE);
                    return null;
                }
            }
        } else {
            // пришел объект
            // объекты складываем в соответствии с relations()
            if (!array_key_exists($name, $this->relations())) {
                $trace = debug_backtrace();
                trigger_error(
                    'Неопределенное свойство в __set(): ' . $name .
                        ' в файле ' . $trace[0]['file'] .
                        ' на строке ' . $trace[0]['line'],
                    E_USER_NOTICE);
                return null;
            }

            $relations = $this->relations();
            $relation = $relations[$name];

            // определяем, какой тип связи
            if ($relation['relationPower'] == RELATION_HAS_ONE) {
                // сначала кладем объект в приватное свойство
                $private = $relation['storageProperty'];
                $this->$private = $value;

                // теперь в поле кладем id объекта
                $field = $relation['storageField'];
                $this->getRecord()->setItemValue($field, $value->getId());
            }
        }
    }
    /**
     * Волшебный геттер
     * @param type $name
     */
    public function __get($name) {
        // проверка на наличие маппинга свойства на поле БД
        $mapping = $this->fieldsMapping();
        if (array_key_exists($name, $mapping)) {
            $name = $mapping[$name];
        }
        // проверяем, не обычное ли это поле
        if (array_key_exists($name, $this->getRecord()->getItems())) {
            // обходной маневр для конвертации mysql-дат в отечественные
            $properties = $this->fieldsProperty();
            if (array_key_exists($name, $properties)) {
                $property = $properties[$name];
                if (array_key_exists("type", $property)) {
                    if ($property["type"] == FIELD_MYSQL_DATE) {
                        $value = $this->getRecord()->getItemValue($name);
                        if (strpos($value, "0000-00-00") !== false) {
                            return "";
                        }
                        return date($property["format"], strtotime($value));
                    }
                }
            }
            return $this->getRecord()->getItemValue($name);
        }

        // поле необычное.
        $relations = $this->relations();
        if (array_key_exists($name, $relations)) {
            $relation = $relations[$name];

            if ($relation['relationPower'] == RELATION_HAS_ONE) {
                $private = $relation['storageProperty'];
                if (is_null($this->$private)) {
                    $key_field = $relation['storageField'];
                    $key_value = $this->$key_field;

                    $managerClass = $relation['managerClass'];
                    $managerGetter = $relation['managerGetObject'];

                    if ($key_value != "") {
                        $this->$private = $managerClass::$managerGetter($key_value);
                    }
                }
                return $this->$private;
            } elseif ($relation['relationPower'] == RELATION_HAS_MANY) {
                $private = $relation['storageProperty'];
                if (is_null($this->$private)) {
                    $table = $relation['storageTable'];
                    $condition = $relation['storageCondition'];
                    $managerClass = $relation['managerClass'];
                    $managerGetter = $relation['managerGetObject'];
                    $managerOrder = null;
                    if (array_key_exists("managerOrder", $relation)) {
                        $managerOrder = $relation['managerOrder'];
                    }

                    $this->$private = new CArrayList();
                    foreach (CActiveRecordProvider::getWithCondition($table, $condition, $managerOrder)->getItems() as $item) {
                        $obj = $managerClass::$managerGetter($item->getId());
                        if (!is_null($obj)) {
                            $this->$private->add($obj->getId(), $obj);
                        }
                    }
                }
                return $this->$private;
            } elseif ($relation['relationPower'] == RELATION_COMPUTED) {
                $private = $relation['storageProperty'];
                if (is_null($this->$private)) {
                    $function = $relation['relationFunction'];
                    $this->$private = $this->$function();
                }
                return $this->$private;
            } elseif ($relation['relationPower'] == RELATION_MANY_TO_MANY) {
                $private = $relation['storageProperty'];
                if (is_null($this->$private)) {
                    $this->$private = new CArrayList();

                    $joinTable = $relation["joinTable"];
                    $rightKey = $relation["rightKey"];
                    $leftCondition = $relation["leftCondition"];
                    $managerClass = $relation["managerClass"];
                    $managerGetter = $relation["managerGetObject"];

                    foreach (CActiveRecordProvider::getWithCondition($joinTable, $leftCondition)->getItems() as $item) {
                        $items = $item->getItems();
                        $obj = $managerClass::$managerGetter($items[$rightKey]);
                        if (!is_null($obj)) {
                            $this->$private->add($obj->id, $obj);
                        }
                    }
                }
                return $this->$private;
            }
        }

        return null;
    }

    /**
     * Автоматическая установка всех полей в записи из запроса
     *
     * @param array $array
     */
    public function setAttributes(array $array) {
        parent::setAttributes($array);
        // дополнительные обработчики
        foreach ($this->fieldsProperty() as $field=>$property) {
            if ($property['type'] == FIELD_MYSQL_DATE) {
                // преобразование даты из отечественного формата в mysql-формат
                $format = "Y-m-d H:i:s";
                if (array_key_exists("mysql_format", $property)) {
                    $format = $property["mysql_format"];
                }
                if (array_key_exists($field, $array)) {
                    if ($array[$field] !== "") {
                        if (strtotime($array[$field]) !== false) {
                            $value = date($format, strtotime($array[$field]));
                            $array[$field] = $value;
                        }
                    } else {
                        $array[$field] = date($format, 0);
                    }
                }
            }
        }
        // поля многие-ко-многим тоже, почему бы и нет
        foreach ($this->relations() as $field=>$relation) {
            if ($relation['relationPower'] == RELATION_MANY_TO_MANY) {
                if (array_key_exists($field, $array)) {
                    $values = $array[$field];
                    $manager = $relation['managerClass'];
                    $getter = $relation['managerGetObject'];
                    $property = $relation['storageProperty'];
                    $this->$property = new CArrayList();
                    foreach ($values as $value) {
                        $related = $manager::$getter($value);
                        if (!is_null($related)) {
                            $this->$property->add($related->getId(), $related);
                        }
                    }
                }
            }
        }
        // поля из базы
        foreach ($array as $key=>$value) {
            if ($this->getDbTable()->getFields()->hasElement($key)) {
                $this->getRecord()->setItemValue($key, $value);
            }
        }
    }

    /**
     * Информация о таблице, в которой хранится запись
     *
     * @return CDbTable
     */
    private function getDbTable() {
        if (is_null($this->_dbTable)) {
            $this->_dbTable = new CDbTable($this->getTable());
        }
        return $this->_dbTable;
    }

    /**
     * Копирование текущего объекта и всех значений его полей
     * кроме ключевого поля id
     *
     * @return mixed
     */
    public function copy() {
        $class = get_class($this);
        $newObj = new $class;
        foreach ($this->getRecord()->getItems() as $key=>$value) {
            if ($key !== "id") {
                $newObj->$key = $value;
            }
        }
        return $newObj;
    }

    /**
     * @return CArrayList|null
     */
    public function getDbTableFields() {
        return $this->getDbTable()->getFields();
    }

    /**
     * Конвертация модельного объекта в объект, готовый для
     * сериализации в json
     *
     * @return stdClass
     */
    public function toJsonObject($relations = true) {
        $obj = new stdClass();
        // добавим поля из таблицы
        foreach ($this->getDbTableFields()->getItems() as $name=>$field) {
            $obj->$name = $this->$name;
        }
        // добавим отношения многие-ко-многим
        foreach ($this->relations() as $field=>$properties) {
            if ($properties["relationPower"] == RELATION_MANY_TO_MANY) {
                $array = array();
                if ($relations) {
                    /**
                     * @var CActiveModel $value
                     */
                    foreach ($this->$field->getItems() as $value) {
                        $array[] = $value->toJsonObject();
                    }
                }
                $obj->$field = $array;
            }
        }
        return $obj;
    }

    /**
     * Обновление модели на основе данных, пришедших из json-контроллера
     *
     * @param $jsonString
     */
    public function updateWithJsonString($jsonString) {
        // данные модели
        $modelData = json_decode($jsonString, true);
        // убираем служебную инфу
        if (array_key_exists("_translation", $modelData)) {
            unset($modelData["_translation"]);
        }
        // попробуем сохранить данные, которые находятся в отношениях
        // многие-ко-многим
        foreach ($this->relations() as $field=>$properties) {
            if ($properties["relationPower"] == RELATION_MANY_TO_MANY) {
                if (array_key_exists($field, $modelData)) {
                    $data = $modelData[$field];
                    // уберем их из модели
                    unset($modelData[$field]);
                    // уберем уже имеющиеся данные из связанной таблицы
                    /**
                     * @var CActiveRecord $ar
                     */
                    foreach (CActiveRecordProvider::getWithCondition(
                        $properties["joinTable"], $properties["leftCondition"]
                    )->getItems() as $ar) {
                        $ar->remove();
                    }
                    // добавим туда новые данные
                    foreach ($data as $value) {
                        $ar = new CActiveRecord(array(
                            $properties["rightKey"] => $value["id"],
                            trim(CUtils::strLeft($properties["leftCondition"], "=")) => $modelData["id"],
                            "id" => null
                        ));
                        $ar->setTable($properties["joinTable"]);
                        $ar->insert();
                    }
                }
            }
        }
        // данные обратно в модель
        foreach ($modelData as $key=>$value) {
            $this->$key = $value;
        }
    }

}
