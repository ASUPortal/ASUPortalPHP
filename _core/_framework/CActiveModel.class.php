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
     * Получить идентификатор последней сохранённой записи
     * 
     * @return int $lastId - идентификатор последней сохранённой записи
     */
    public function getLastInsertId() {
        if (is_object(CApp::getApp()->getDbConnection())) {
            $lastId = CApp::getApp()->getDbConnection()->lastInsertId();
        } else {
            $lastId = mysql_insert_id();
        }
        return $lastId;
    }
    /**
     * Сохранение или обновление данных модели
     * 
     * @return int $lastId - идентификатор последней сохранённой записи
     */
    public function save() {
        /**
         * Пусть сохранение будет транзакционной операцией
         * Мало ли что, вообще
         */
        $transaction = new CTransaction();
        try {
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
                $lastId = $this->getLastInsertId();
                $this->setId($lastId);
                // для первого сохранения попытаемся сразу сохранить многие-ко-многим отношения
                foreach ($this->relations() as $field=>$relation) {
                	if ($relation['relationPower'] == RELATION_MANY_TO_MANY) {
                		$this->saveRelationManyToMany($field, $relation, $this->getId(), true);
                	}
                }
            } else {
                $lastId = $this->updateModel();
                // обновляем многие-ко-многим отношения только если модель не поддерживает версионирование
                if (!is_a($this, "IVersionControl")) {
                	foreach ($this->relations() as $field=>$relation) {
                		if ($relation['relationPower'] == RELATION_MANY_TO_MANY) {
                			$this->saveRelationManyToMany($field, $relation, $this->getId(), false);
                		}
                	}
                }
            }
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception($e);
        }
        $transaction->commit();
        /**
         * Если в кэше есть, то обновим
         */
        $keySeek = $this->getTable() . "_" . $this->getId();
        if (CApp::getApp()->cache->hasCache($keySeek)) {
            CApp::getApp()->cache->set($keySeek, $this);
        }
        return $lastId;
    }
    /**
     * Сохранение отношений многие-ко-многим
     * 
     * @param string $field
     * @param array $relation
     * @param int $conditionId
     * @param boolean $lastVersion
     */
    private function saveRelationManyToMany($field, $relation, $conditionId, $lastVersion) {
    	// сохраним старое значение на всякий случай
    	$currentValue = $this->$field;
    	// если модель не поддерживает версионирование, удалим все старые
    	if (!is_a($this, "IVersionControl")) {
    		foreach (CActiveRecordProvider::getWithCondition($relation['joinTable'], $relation['leftCondition'])->getItems() as $ar) {
    			$ar->remove();
    		}
    	}
    	// теперь сохраним новые
    	foreach ($currentValue->getItems() as $key=>$value) {
    		$ar = new CActiveRecord(array(
    				CUtils::strLeft($relation['leftCondition'], " ") => $conditionId,
    				$relation['rightKey'] => $key,
    				"id" => null
    		));
    		// если модель поддерживает версионирование, текущая запись становится архивной
    		if (array_key_exists("targetClass", $relation)) {
    			$targetClass = new $relation["targetClass"];
    			if (is_a($targetClass, "IVersionControl")) {
    				$this->fillVersionControlFields($ar, $this->getId(), $lastVersion);
    			}
    		}
    		$ar->setTable($relation['joinTable']);
    		$ar->insert();
    	}
    }
    /**
     * Сохранение новой модели
     */
    private function saveModel() {
    	// если модель поддерживает версионирование, заполняем ей необходимые поля
    	if (is_a($this, "IVersionControl")) {
    		// новую запись сохраним с версией 0
    		$this->fillVersionControlFields($this->getRecord(), 0, true);
    		$this->getRecord()->insert();
    		// получим идентификатор, сгенерированный при последнем INSERT-запросе
    		$lastId = $this->getLastInsertId();
    		// обновим запись с версией $lastId
    		$currentAr = CActiveRecordProvider::getById($this->getTable(), $lastId);
    		$this->fillVersionControlFields($currentAr, $lastId, true);
    		$currentAr->update();
    	} else {
    		$this->getRecord()->insert();
    	}
    }
    /**
     * Обновление существующей модели
     * 
     * @return int $lastId - идентификатор последней сохранённой записи
     */
    private function updateModel() {
        // получим идентификатор, сгенерированный при последнем INSERT-запросе
        $lastId = $this->getLastInsertId();
        // если эта модель поддерживает версионирование,
        // то делаем копию текущей записи, при этом текущую запись не обновляем
        if (is_a($this, "IVersionControl")) {
            $this->fillVersionControlFields($this->getRecord(), $this->getId(), true);
            $this->getRecord()->insert();
            // получим идентификатор, сгенерированный при последнем INSERT-запросе
            $lastId = $this->getLastInsertId();
            foreach ($this->relations() as $field=>$relation) {
            	if ($relation['relationPower'] == RELATION_MANY_TO_MANY) {
            		$this->saveRelationManyToMany($field, $relation, $lastId, true);
            	}
            }
            // для текущей записи меняем признак последней версии
            $this->setIsLastVersion($this->getRecord(), 0);
            $this->getRecord()->update();
        } else {
            $this->getRecord()->update();
        }
        return $lastId;
    }
    /**
     * Заполнить поля для версионирования
     * 
     * @param CActiveRecord $record - изменяемая запись
     * @param int $version - id версии записи
     * @param boolean $lastVersion - признак последней версии
     */
    private function fillVersionControlFields(CActiveRecord $record, $version, $lastVersion) {
        $record->setItemValue("_version_of", $version);
        $record->setItemValue("_created_at", date('Y-m-d G:i:s'));
        $record->setItemValue("_created_by", CSession::getCurrentPerson()->getId());
        $this->setIsLastVersion($record, $lastVersion);
    }
    /**
     * Установить признак последней версии
     * 
     * @param CActiveRecord $record
     * @param int $version
     */
    public function setIsLastVersion(CActiveRecord $record, $version) {
        $record->setItemValue("_is_last_version", $version);
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

        $transaction = new CTransaction();
        try {
            $this->getRecord()->remove();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception($e);
        }
        $transaction->commit();
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
                // обратимся к родительскому методу
                parent::__set($name, $value);
                return;
            }

            $relations = $this->relations();
            $relation = $relations[$name];

            // разрешим не указывать
            if (!array_key_exists("storageProperty", $relation)) {
                $relation["storageProperty"] = "_".$name;
            }

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
                        if (strpos($value, "0000-00-00") !== false or $value == null) {
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
            // разрешим не указывать
            if (!array_key_exists("storageProperty", $relation)) {
                $relation["storageProperty"] = "_".$name;
            }

            if ($relation['relationPower'] == RELATION_HAS_ONE) {
                $private = $relation['storageProperty'];
                if (is_null($this->$private)) {
                    $key_field = $relation['storageField'];
                    $key_value = $this->$key_field;

                    $useManager = true;
                    if (array_key_exists("targetClass", $relation)) {
                        $targetClass = $relation["targetClass"];
                        $useManager = false;
                    } else {
                        $managerClass = $relation['managerClass'];
                        $managerGetter = $relation['managerGetObject'];
                    }

                    if ($useManager) {
                        $obj = $managerClass::$managerGetter($key_value);
                    } else {
                        $targetClass = "get".mb_substr($targetClass, 1);
                        $obj = CBaseManager::$targetClass($key_value);
                    }

                    $this->$private = $obj;
                }
                return $this->$private;
            } elseif ($relation['relationPower'] == RELATION_HAS_MANY) {
                $private = $relation['storageProperty'];
                /**
                 * Проверим, вдруг разрешено использование кэш
                 * и данные кэше уже есть
                 */
                $useCache = false;
                if (array_key_exists("useCache", $relation)) {
                    $useCache = $relation["useCache"];
                }
                if ($useCache) {
                    $cacheKey = get_class($this)."_property_".$name."_".$this->getId();
                    if (CApp::getApp()->cache->hasCache($cacheKey)) {
                        $valueFromCache = CApp::getApp()->cache->get($cacheKey);
                        $this->$private = $valueFromCache;
                    }
                }
                if (is_null($this->$private)) {
                    $table = $relation['storageTable'];
                    $condition = $relation['storageCondition'];
                    $useManager = true;
                    if (array_key_exists("targetClass", $relation)) {
                        $targetClass = $relation["targetClass"];
                        $useManager = false;
                    } else {
                        $managerClass = $relation['managerClass'];
                        $managerGetter = $relation['managerGetObject'];
                    }
                    $managerOrder = null;
                    if (array_key_exists("managerOrder", $relation)) {
                        $managerOrder = $relation['managerOrder'];
                    }

                    $this->$private = new CArrayList();
                    foreach (CActiveRecordProvider::getWithCondition($table, $condition, $managerOrder)->getItems() as $item) {
                        if ($useManager) {
                            $obj = $managerClass::$managerGetter($item->getId());
                        } else {
                            $obj = new $targetClass($item);
                        }
                        if (!is_null($obj)) {
                            if (is_object($obj)) {
                                $this->$private->add($obj->getId(), $obj);
                            } else {
                                $this->$private->add($this->$private->getCount(), $obj);
                            }
                        }
                    }
                    if ($useCache) {
                        CApp::getApp()->cache->set($cacheKey, $this->$private, 60);
                    }
                }
                if (array_key_exists("comparator", $relation)) {
                    $comparator = new $relation['comparator']();
                    $this->$private = CCollectionUtils::sort($this->$private, $comparator);
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
                /**
                 * Проверим, вдруг разрешено использование кэш
                 * и данные кэше уже есть
                 */
                $useCache = false;
                if (array_key_exists("useCache", $relation)) {
                    $useCache = $relation["useCache"];
                }
                if ($useCache) {
                    $cacheKey = get_class($this)."_property_".$name."_".$this->getId();
                    if (CApp::getApp()->cache->hasCache($cacheKey)) {
                        $valueFromCache = CApp::getApp()->cache->get($cacheKey);
                        $this->$private = $valueFromCache;
                    }
                }
                if (is_null($this->$private)) {
                    $this->$private = new CArrayList();

                    $joinTable = $relation["joinTable"];
                    $rightKey = $relation["rightKey"];
                    $leftCondition = $relation["leftCondition"];

                    $useManager = true;
                    if (array_key_exists("targetClass", $relation)) {
                        $useManager = false;
                    } else {
                        $managerClass = $relation["managerClass"];
                        $managerGetter = $relation["managerGetObject"];
                    }

                    foreach (CActiveRecordProvider::getWithCondition($joinTable, $leftCondition)->getItems() as $item) {
                        $items = $item->getItems();
                        if ($useManager) {
                            $obj = $managerClass::$managerGetter($items[$rightKey]);
                        } else {
                            $method = "get".mb_substr($relation["targetClass"], 1);
                            $obj = CBaseManager::$method($items[$rightKey]);
                        }

                        if (!is_null($obj)) {
                            $this->$private->add($obj->id, $obj);
                        }
                    }

                    if ($useCache) {
                        CApp::getApp()->cache->set($cacheKey, $this->$private, 60);
                    }
                }
                if (array_key_exists("comparator", $relation)) {
                    $comparator = new $relation['comparator']();
                    $this->$private = CCollectionUtils::sort($this->$private, $comparator);
                }
                return $this->$private;
            }
        }

        return parent::__get($name);
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
                        $array[$field] = "0000-00-00";
                    }
                }
            }
        }
        // поля многие-ко-многим тоже, почему бы и нет
        foreach ($this->relations() as $field=>$relation) {
            if ($relation['relationPower'] == RELATION_MANY_TO_MANY) {
                if (array_key_exists($field, $array)) {
                    $values = $array[$field];

                    $useManager = true;
                    if (array_key_exists("targetClass", $relation)) {
                        $useManager = false;
                    } else {
                        $manager = $relation['managerClass'];
                        $getter = $relation['managerGetObject'];
                    }
                    // разрешим не указывать
                    if (!array_key_exists("storageProperty", $relation)) {
                        $relation["storageProperty"] = "_".$field;
                    }
                    $property = $relation['storageProperty'];
                    $this->$property = new CArrayList();
                    foreach ($values as $value) {
                        if ($useManager) {
                            $related = $manager::$getter($value);
                        } else {
                            $method = "get".mb_substr($relation["targetClass"], 1);
                            $related = CBaseManager::$method($value);
                        }
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
            } elseif ($properties["relationPower"] == RELATION_HAS_MANY) {
                $array = array();
                if ($relations) {
                    foreach ($this->$field->getItems() as $value) {
                        if (is_object($value)) {
                            $array[] = $value->toJsonObject();
                        } else {
                            $array[] = $value;
                        }
                    }
                }
                $obj->$field = $array;
            } elseif ($properties["relationPower"] == RELATION_HAS_ONE) {
                if (!is_null($this->$field)) {
                    $obj->$field = $this->$field->toJsonObject();
                }
            }
        }
        return $obj;
    }

    /**
     * Обновление модели на основе данных, пришедших из json-контроллера
     *
     * @param $jsonString
     * @return array
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
                    if (array_key_exists("id", $modelData)) {
                        foreach (CActiveRecordProvider::getWithCondition(
                            $properties["joinTable"], trim(CUtils::strLeft($properties["leftCondition"], "="))."=".$modelData["id"]
                        )->getItems() as $ar) {
                            $ar->remove();
                        }
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
            } elseif ($properties["relationPower"] == RELATION_HAS_MANY) {
                if (array_key_exists($field, $modelData)) {
                    $data = $modelData[$field];
                    // уберем данные из модели
                    unset($modelData[$field]);
                    // если в свойствах отношения указан целевой класс, то
                    // будем обновлять автоматом
                    if (array_key_exists("targetClass", $properties)) {
                        // получим список записей, которые уже есть
                        $targetClass = $properties["targetClass"];
                        /**
                         * @var CActiveModel $targetObj
                         */
                        $targetObj = new $targetClass();
                        $docsToRemove = array();
                        // его может не быть, если запись новая
                        if (array_key_exists("id", $modelData)) {
                            $items = CActiveRecordProvider::getWithCondition($targetObj->getTable(), trim(CUtils::strLeft($properties["storageCondition"], "="))."=".$modelData["id"]);
                            /**
                             * @var CActiveRecord $item
                             */
                            foreach ($items->getItems() as $item) {
                                $docsToRemove[] = $item->getId();
                            }
                        }
                        /**
                         * @var string $item
                         */
                        foreach ($data as $item) {
                            // полученные данные обратно в json, чтобы
                            // можно было все сделать одинаково рекурсивно
                            $childJsonData = json_encode($item);
                            // создадим экземпляр целевого класса
                            /**
                             * @var CActiveModel $targetObj
                             */
                            $targetObj = new $targetClass();
                            $targetObj->updateWithJsonString($childJsonData);
                            $targetObj->save();
                            // уберем из списка добавленную запись
                            if (in_array($targetObj->getId(), $docsToRemove)) {
                                unset($docsToRemove[array_search($targetObj->getId(), $docsToRemove)]);
                            }
                        }
                        // удалим элементы из списка на удаление - мы
                        // их удалили и вместе с другими данными с клиента
                        // они не пришли
                        if (count($docsToRemove) > 0) {
                            CActiveRecordProvider::removeWithCondition($targetObj->getTable(), "id in (".implode(", ", $docsToRemove).")");
                        }
                    }
                }
            }
        }
        // данные обратно в модель
        foreach ($modelData as $key=>$value) {
            $this->$key = $value;
        }
        return $modelData;
    }

}
