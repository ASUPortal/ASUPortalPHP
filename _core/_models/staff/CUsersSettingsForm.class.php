<?php

class CUsersSettingsForm extends CFormModel {
    public $users;
    
    public static function getClassName() {
    	return __CLASS__;
    }
    
    public function attributeLabels() {
    	return array(
    			"dashboard_enabled_groups" => "Использовать Рабочий стол",
    			"dashboard_show_birthdays_groups" => "Показывать дни рождения",
    			"dashboard_show_messages_groups" => "Показывать сообщения",
    			"dashboard_show_all_tasks_groups" => "Показывать все задачи",
    			"dashboard_check_messages_groups" => "Проверять сообщения"
    	);
    }

}