<?php

class CBiography extends CActiveModel{
    protected $_table = TABLE_BIOGRAPHY;
    
    public function attributeLabels() {
        return array(
            "main_text" => "Биография",
        	"image" => "Фото",
            "user_id" => "Пользователь"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "main_text"
            )
        );
    }
    public function fieldsProperty() {
    	return array(
    			'image' => array(
    					'type' => FIELD_UPLOADABLE,
    					'upload_dir' => CORE_CWD.CORE_DS."images".CORE_DS."lects".CORE_DS
    			)
    	);
    }
    /**
     * @return CPerson
     */
    public function getUser() {
		$users = CStaffManager::getUser($this->user_id);
 			if (!is_null($users)) {
				$user = $users->getPerson();
			}
        return $user;
    }
}