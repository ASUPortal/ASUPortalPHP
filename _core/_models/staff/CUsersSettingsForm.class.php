<?php

class CUsersSettingsForm extends CFormModel {
    public $users;
    
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

}