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

    protected $_reports = null;

    public function relations() {
        return array(
            "reports" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_reports",
                "joinTable" => TABLE_DASHBOARD_REPORTS,
                "leftCondition" => "settings_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "report_id",
                "managerClass" => "CReportManager",
                "managerGetObject" => "getReport"
            ),
        );
    }

    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "dashboard_enabled" => "Использовать Рабочий стол",
            "dashboard_show_birthdays" => "Показывать дни рождения",
            "dashboard_show_messages" => "Показывать сообщения",
            "dashboard_show_all_tasks" => "Показывать все задачи",
            "dashboard_check_messages" => "Проверять сообщения",
            "portal_input_size" => "Размер полей ввода"
        );
    }
    public static function getInputSizes() {
        return array(
            "span5" => "5",
            "span6" => "6",
            "span7" => "7",
            "span8" => "8",
            "span9" => "9",
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
    public function isCheckMessages() {
        return $this->dashboard_check_messages == 1;
    }
}
