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
            /**
             * Если модель поддерживает ACL, то заранее прописываем ей читателей
             * и редакторов из числа установленных по умолчанию
             */
            if ($this->isACLEnabled()) {
                $table = CACLManager::getACLTable($this->getTable());
                if (!is_null($table)) {
                    $this->setReaders($table->getDefaultReaders());
                    $this->setAuthors($table->getDefaultAuthors());
                }
            }
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
     * Дополнительные отношения для работы с ACL
     *
     * @return array
     */
    private function getACLRelations() {
        return array(
            "readers" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_readers",
                "relationFunction" => "getReaders"
            ),
            "authors" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_authors",
                "relationFunction" => "getAuthors"
            )
        );
    }

    /**
     * Объекты доступа, которые видят данную запись
     *
     * @return CArrayList|null
     */
    public function getReaders() {
        if (is_null($this->_readers)) {
            $this->_readers = new CArrayList();
            if ($this->isACLEnabled()) {
                if ($this->getId()) {
                    foreach (CActiveRecordProvider::getWithCondition($this->getTable().ACL_ENTRIES, "object_id=".$this->getId()." AND level=1")->getItems() as $item) {
                        // это пользователь
                        if ($item->getItemValue("entry_type") == 1) {
                            $entry = CStaffManager::getUser($item->getItemValue("entry_id"));
                            if (!is_null($entry)) {
                                $this->_readers->add($this->_readers->getCount(), $entry);
                            }
                            // это группа пользователей
                        } elseif ($item->getItemValue("entry_type") == 2) {
                            $entry = CStaffManager::getUserGroup($item->getItemValue("entry_id"));
                            if (!is_null($entry)) {
                                $this->_readers->add($this->_readers->getCount(), $entry);
                            }
                        }
                    }
                }
            }
        }
        return $this->_readers;
    }

    /**
     * Объекты доступа, которые данную запись могут изменять
     *
     * @return CArrayList|null
     */
    public function getAuthors() {
        if (is_null($this->_authors)) {
            $this->_authors = new CArrayList();
            if ($this->isACLEnabled()) {
                if ($this->getId()) {
                    foreach (CActiveRecordProvider::getWithCondition($this->getTable().ACL_ENTRIES, "object_id=".$this->getId()." AND level=2")->getItems() as $item) {
                        // это пользователь
                        if ($item->getItemValue("entry_type") == 1) {
                            $entry = CStaffManager::getUser($item->getItemValue("entry_id"));
                            if (!is_null($entry)) {
                                $this->_authors->add($this->_authors->getCount(), $entry);
                            }
                            // это группа пользователей
                        } elseif ($item->getItemValue("entry_type") == 2) {
                            $entry = CStaffManager::getUserGroup($item->getItemValue("entry_id"));
                            if (!is_null($entry)) {
                                $this->_authors->add($this->_authors->getCount(), $entry);
                            }
                        }
                    }
                }
            }
        }
        return $this->_authors;
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
            $this->setId(mysql_insert_id());
        } else {
            $this->updateModel();
        }
        /**
         * Если модель с поддержкой ACL, то, если readers/authors установлены,
         * обновляем их вместе с записью
         */
        if ($this->isACLEnabled()) {
            $this->saveACLEntries();
        }
    }

    /**
     * Сохранение ACL записей. Выделил в отдельный метод чтобы можно
     * было их обновлять без пересохранения самой записи
     */
    public function saveACLEntries() {
        // если читатели или редакторы установлены, то удаляем все старые записи
        if (!is_null($this->_readers) && !is_null($this->_authors)) {
            $q = new C2Query();
            $q->query("DELETE FROM ".$this->getTable().ACL_USERS." WHERE object_id=".$this->getId())->execute();
            $q = new C2Query();
            $q->query("DELETE FROM ".$this->getTable().ACL_ENTRIES." WHERE object_id=".$this->getId())->execute();
        }
        // сохраняем читателей документа
        if (!is_null($this->_readers)) {
            foreach ($this->_readers->getItems() as $entry) {
                $model = new CActiveModel();
                $model->getRecord()->setTable($this->getTable().ACL_ENTRIES);
                $model->object_id = $this->getId();
                $model->entry_type = $entry->getType();
                $model->entry_id = $entry->getId();
                $model->level = ACL_LEVEL_READER;
                $model->save();
            }
            // получаем реальных пользователей
            $users = new CArrayList();
            foreach ($this->_readers->getItems() as $entry) {
                if ($entry->getType() == ACL_ENTRY_USER) {
                    $users->add($entry->getId(), $entry);
                } elseif ($entry->getType() == ACL_ENTRY_GROUP) {
                    foreach ($entry->getUsersInHierarchy()->getItems() as $user) {
                        $users->add($user->getId(), $user);
                    }
                }
            }
            // сохраняем реальных пользователей
            foreach ($users->getItems() as $user) {
                $model = new CActiveModel();
                $model->getRecord()->setTable($this->getTable().ACL_USERS);
                $model->object_id = $this->getId();
                $model->user_id = $user->getId();
                $model->level = ACL_LEVEL_READER;
                $model->save();
            }
        }
        // сохраняем редакторов документа
        if (!is_null($this->_authors)) {
            foreach ($this->_authors->getItems() as $entry) {
                $model = new CActiveModel();
                $model->getRecord()->setTable($this->getTable().ACL_ENTRIES);
                $model->object_id = $this->getId();
                $model->entry_type = $entry->getType();
                $model->entry_id = $entry->getId();
                $model->level = ACL_LEVEL_AUTHOR;
                $model->save();
            }
            // получаем реальных пользователей
            $users = new CArrayList();
            foreach ($this->_authors->getItems() as $entry) {
                if ($entry->getType() == ACL_ENTRY_USER) {
                    $users->add($entry->getId(), $entry);
                } elseif ($entry->getType() == ACL_ENTRY_GROUP) {
                    foreach ($entry->getUsersInHierarchy()->getItems() as $user) {
                        $users->add($user->getId(), $user);
                    }
                }
            }
            // сохраняем реальных пользователей
            foreach ($users->getItems() as $user) {
                $model = new CActiveModel();
                $model->getRecord()->setTable($this->getTable().ACL_USERS);
                $model->object_id = $this->getId();
                $model->user_id = $user->getId();
                $model->level = ACL_LEVEL_READER;
                $model->save();
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
        // добивалка для моделей с поддержкой ACL
        if ($this->isACLEnabled()) {
            $relations = array_merge($relations, $this->getACLRelations());
        }
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
            $this->getRecord()->setItemValue($key, $value);
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
}
