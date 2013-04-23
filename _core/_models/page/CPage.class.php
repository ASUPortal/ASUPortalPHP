<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 13.04.13
 * Time: 11:14
 * To change this template use File | Settings | File Templates.
 */

class CPage extends CActiveModel{
    protected $_table = TABLE_PAGES;
    protected $_author = null;
    public function attributeLabels() {
        return array(
            "title" => "Название страницы",
            "user_id_insert" => "Пользователь, добавивший страницу"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title"
            )
        );
    }
    public function relations() {
        return array(
            "author" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_author",
                "relationFunction" => "getAuthor"
            )
        );
    }

    /**
     * @return CPerson
     */
    public function getAuthor() {
        if (is_null($this->_author)) {
            $user = CStaffManager::getUser($this->user_id_insert);
            if (!is_null($user)) {
                $this->_author = $user->getPerson();
            }
        }
        return $this->_author;
    }
}