<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 24.02.13
 * Time: 18:16
 * To change this template use File | Settings | File Templates.
 */
class CDiplomPreview extends CActiveModel {
    protected $_table = TABLE_DIPLOM_PREVIEWS;
    protected $_commission = null;
    protected $_student = null;
    protected $_diploms = null;

    public function relations() {
        return array(
            "commission" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_commission",
                "storageField" => "comm_id",
                "managerClass" => "CSABManager",
                "managerGetObject" => "getPreviewCommission"
            ),
			"student" => array(
				"relationPower" => RELATION_HAS_ONE,
				"storageProperty" => "_student",
				"storageField" => "student_id",
				"managerClass" => "CStaffManager",
				"managerGetObject" => "getStudent"
			),
			"diplom" => array(
				"relationPower" => RELATION_HAS_ONE,
				"storageProperty" => "_diploms",
				"storageField" => "diplom_id",
				"managerClass" => "CStaffManager",
				"managerGetObject" => "getDiplom"
			),
			"reviewer" => array(
				"relationPower" => RELATION_HAS_ONE,
				"storageProperty" => "_reviewer",
				"storageField" => "recenz_id",
				"managerClass" => "CStaffManager",
				"managerGetObject" => "getPerson"
			)
        );
    }
    public function attributeLabels() {
    	return array(
    			"student_id" => "Студент",
    			"diplom_percent" => "Процент выполнения работы",
    			"another_view" => "Прослушать еще раз",
    			"recenz_id" => "Рецензент",
    			"date_preview" => "Дата предзащиты",
    			"comm_id" => "Комиссия",
    			"comment" => "Примечание",
    			"diplom_id" => "Тема диплома",
    	);
    }
    public function fieldsProperty() {
    	return array(
    		"date_preview" => array(
    			"type" => FIELD_MYSQL_DATE,
    			"format" => "d.m.Y"
    		)
    	);
    }
    public function getPreviewDate() {
    	return date("d.m.Y", strtotime($this->date_preview));
    }
}
