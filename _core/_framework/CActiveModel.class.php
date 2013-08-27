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
class CActiveModel extends CModel{
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
            $this->setId(CApp::getApp()->getDbConnection()->lastInsertId());
        } else {
            $this->updateModel();
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
        $this->getRecord()->update();
    }
    /**
     * Установить значение id
     *
     * @param $id
     */
    public function setId($id) {
        $this->getRecord()->setItemValue("id", $id);
    }
    /**
     * Удаление текущей записи из БД
     */
    public function remove() {
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
}
