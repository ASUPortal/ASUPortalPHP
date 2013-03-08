<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 22.12.12
 * Time: 11:35
 * To change this template use File | Settings | File Templates.
 */
class CUserSettings extends CActiveModel {
    protected $_table = TABLE_USER_SETTINGS;
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "dashboard_enabled" => "Использовать Рабочий стол",
            "dashboard_show_birthdays" => "Показывать дни рождения",
            "dashboard_show_messages" => "Показывать сообщения",
            "dashboard_show_all_tasks" => "Показывать все задачи",
            "dashboard_check_messages" => "Проверять сообщения"
        );
    }
    public function isDashboardEnabled() {
        return $this->dashboard_enabled == 1;
    }
    public function isShowBirthdays() {
        return $this->dashboard_show_birthdays == 1;
    }
    public function isShowMessages() {
        return $this->dashboard_show_messages == 1;
    }
}
