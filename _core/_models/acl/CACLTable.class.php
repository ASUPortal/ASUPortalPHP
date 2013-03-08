<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 04.01.13
 * Time: 18:06
 * To change this template use File | Settings | File Templates.
 */
class CACLTable extends CActiveModel {
    protected $_table = TABLE_ACL_TABLES;
    protected $_aclControlEnabled = true;
    protected $_defaultReaders = null;
    protected $_defaultAuthors = null;
    public function relations() {
        return array(
            "default_readers" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_defaultReaders",
                "relationFunction" => "getDefaultReaders"
            ),
            "default_authors" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_defaultAuthors",
                "relationFunction" => "getDefaultAuthors"
            )
        );
    }
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "table" => "Таблица",
            "title" => "Русское название таблицы",
            "description" => "Описание",
            "readers" => "Читатели",
            "authors" => "Авторы",
            "default_authors" => "Редакторы по умолчанию",
            "default_readers" => "Читатели по умолчанию"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "table",
                "title"
            )
        );
    }
    /**
     * Читатели по умолчанию для таблицы доступа
     *
     * @return CArrayList|null
     */
    public function getDefaultReaders() {
        if (is_null($this->_defaultReaders)) {
            $this->_defaultReaders = new CArrayList();
            if ($this->isACLEnabled()) {
                if ($this->getId()) {
                    foreach (CActiveRecordProvider::getWithCondition(TABLE_ACL_DEFAULTS, "table_id=".$this->getId()." AND level=1")->getItems() as $item) {
                        // это пользователь
                        if ($item->getItemValue("entry_type") == 1) {
                            $entry = CStaffManager::getUser($item->getItemValue("entry_id"));
                            if (!is_null($entry)) {
                                $this->_defaultReaders->add($this->_defaultReaders->getCount(), $entry);
                            }
                            // это группа пользователей
                        } elseif ($item->getItemValue("entry_type") == 2) {
                            $entry = CStaffManager::getUserGroup($item->getItemValue("entry_id"));
                            if (!is_null($entry)) {
                                $this->_defaultReaders->add($this->_defaultReaders->getCount(), $entry);
                            }
                        }
                    }
                }
            }
        }
        return $this->_defaultReaders;
    }

    /**
     * Авторы по умолчанию для таблицы доступа
     *
     * @return CArrayList|null
     */
    public function getDefaultAuthors() {
        if (is_null($this->_defaultAuthors)) {
            $this->_defaultAuthors = new CArrayList();
            if ($this->isACLEnabled()) {
                if ($this->getId()) {
                    foreach (CActiveRecordProvider::getWithCondition(TABLE_ACL_DEFAULTS, "table_id=".$this->getId()." AND level=2")->getItems() as $item) {
                        // это пользователь
                        if ($item->getItemValue("entry_type") == 1) {
                            $entry = CStaffManager::getUser($item->getItemValue("entry_id"));
                            if (!is_null($entry)) {
                                $this->_defaultAuthors->add($this->_defaultAuthors->getCount(), $entry);
                            }
                            // это группа пользователей
                        } elseif ($item->getItemValue("entry_type") == 2) {
                            $entry = CStaffManager::getUserGroup($item->getItemValue("entry_id"));
                            if (!is_null($entry)) {
                                $this->_defaultAuthors->add($this->_defaultAuthors->getCount(), $entry);
                            }
                        }
                    }
                }
            }
        }
        return $this->_defaultAuthors;
    }
}
