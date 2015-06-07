<?php

class CQuestion extends CActiveModel{
    protected $_table = TABLE_QUESTION_TO_USERS;
    protected $_stat = null;
    
    public function relations() {
    	return array(
    			"stat" => array(
    					"relationPower" => RELATION_HAS_ONE,
    					"storageProperty" => "_stat",
    					"storageField" => "status",
    					"managerClass" => "CQuestionManager",
    					"managerGetObject" => "getQuestionStatus"
    			),
    			"user" => array(
    					"relationPower" => RELATION_HAS_ONE,
    					"storageProperty" => "_user",
    					"storageField" => "user_id",
    					"managerClass" => "CStaffManager",
    					"managerGetObject" => "getUser"
    			),
    	);
    }
    
    public function attributeLabels() {
        return array(
            "datetime_quest" => "Дата вопроса",
			"datetime_answ" => "Дата ответа",
			"question_text" => "Текст вопроса",
			"contact_info" => "Контактная информация",
			"stat" => "Статус",
        	"status" => "Статус",
        	"st.name" => "Статус",
			"answer_text" => "Текст ответа",
			"user" => "Адресат",
        	"user_id" => "Адресат",
        	"quest.user_id" => "Адресат"
        );
    }
    
    public function fieldsProperty() {
    	return array(
    			"datetime_quest" => array(
    					"type" => FIELD_MYSQL_DATE,
    					"format" => "d.m.Y H:i:s"
    			),
    			"datetime_answ" => array(
    					"type" => FIELD_MYSQL_DATE,
    					"format" => "d.m.Y H:i:s"
    			)
    	);
    }
    
    public function validationRules() {
        return array(
            "required" => array(
                "question_text"
            )
        );
    }

}