<?php
class CDashboardItemForm extends CFormModel {
	public $users;

	public static function getClassName() {
		return __CLASS__;
	}
	
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "link" => "Ссылка",
            "icon" => "Значок",
            "parent_id" => "Родительский элемент"
        );
    }
    
}