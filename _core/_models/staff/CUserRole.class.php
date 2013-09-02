<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 11.06.12
 * Time: 17:22
 * To change this template use File | Settings | File Templates.
 */
class CUserRole extends CActiveModel {
    protected $_table = TABLE_USER_ROLES;
    protected $_menu = null;
    public $level;
    public $hidden = 0;
    public function getName() {
        return $this->getRecord()->getItemValue("name");
    }
    public function relations() {
        return array(
            "menu" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_menu",
                "relationFunction" => "getMenu"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "name" => "Название задачи",
            "alias" => "Псевдоним задачи",
            "url" => "Адрес",
            "menu_name_id" => "Группа меню",
            "comment" => "Комментарий",
            "hidden" => "Не показывать в списке задач"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "name",
                "alias",
            )
        );
    }

    /**
     * Пункт меню, к которому привязана задача
     *
     * @return CTerm
     */
    public function getMenu() {
        if (is_null($this->_menu)) {
            $this->_menu = CTaxonomyManager::getLegacyTerm($this->menu_name_id, "task_menu_names");
        }
        return $this->_menu;
    }
}
