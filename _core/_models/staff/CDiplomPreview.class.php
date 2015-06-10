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
    protected $_diplom = null;

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
				"storageProperty" => "_diplom",
				"storageField" => "diplom_id",
				"managerClass" => "CStaffManager",
				"managerGetObject" => "getDiplom"
			)
        );
    }
    public function attributeLabels() {
    	return array(
    			"student_id" => "Студент",
    			"student.fio" => "Студент",
    			"diplom_percent" => "Процент выполнения работы",
    			"another_view" => "Прослушать еще раз",
    			"date_preview" => "Дата предзащиты",
    			"person.fio" => "Рецензент",
    			"comm_id" => "Комиссия",
    			"comm.name" => "Комиссия",
    			"comment" => "Примечание",
    			"diplom_id" => "Тема ВКР",
    			"diplom.dipl_name" => "Тема ВКР",
    			"st_group.name" => "Группа"
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
