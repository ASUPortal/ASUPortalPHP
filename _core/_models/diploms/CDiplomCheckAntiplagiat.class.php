<?php
/**
 * Проверка тем ВКР на антиплагиат
 */
class CDiplomCheckAntiplagiat extends CActiveModel{
    protected $_table = TABLE_DIPLOM_CHECKS_ON_ANTIPLAGIAT;

    protected function relations() {
        return array(
			"diplom" => array(
				"relationPower" => RELATION_HAS_ONE,
				"storageProperty" => "_diplom",
				"storageField" => "diplom_id",
				"managerClass" => "CStaffManager",
				"managerGetObject" => "getDiplom"
			),
            "responsible" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_responsible",
                "storageField" => "responsible_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "diplom_id" => "Тема ВКР",
            "responsible_id" => "Ответственный за проверку",
            "check_date_on_antiplagiat" => "Дата проверки на антиплагиат",
            "check_time_on_antiplagiat" => "Время проверки на антиплагиат",
            "borrowing_percent" => "Процент заимствований",
            "citations_percent" => "Процент цитирования",
            "originality_percent" => "Процент оригинальности",
            "comments_on_antiplagiat" => "Комментарии"
        );
    }
    
    public function fieldsProperty() {
    	return array(
    		"check_date_on_antiplagiat" => array(
    				"type"  => FIELD_MYSQL_DATE,
    				"format" => "d.m.Y"
    		)
    	);
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "responsible_id"
            ),
        	"checkdate" => array(
    			"check_date_on_antiplagiat"
    		)
        );
    }

}