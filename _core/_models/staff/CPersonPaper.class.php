<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 26.01.14
 * Time: 15:38
 * To change this template use File | Settings | File Templates.
 */

class CPersonPaper extends CActiveModel{
    protected $_table = TABLE_PERSON_DISSER;
    protected $_person = null;
    protected $_scienceManager = null;
    protected $_edForm = null;
    public $type = 0;

    public function fieldsProperty() {
        return array(
            'file_attach' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS."library".CORE_DS."anketa".CORE_DS."kandid".CORE_DS
            ),
            'date_begin' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            ),
            'date_out' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            ),
            'date_end' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            ),
            'dis_sov_date' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            ),
            'vak_date' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            )
        );
    }

    public function relations() {
    	return array(
            "person" => array(
					"relationPower" => RELATION_HAS_ONE,
					"storageProperty" => "_person",
					"storageField" => "kadri_id",
					"managerClass" => "CStaffManager",
					"managerGetObject" => "getPerson"
            ),
    		"scienceManager" => array(
    				"relationPower" => RELATION_HAS_ONE,
    				"storageProperty" => "_scienceManager",
    				"storageField" => "scinceMan",
    				"managerClass" => "CStaffManager",
    				"managerGetObject" => "getPerson"
    		),
            "educationForm" => array(
					"relationPower" => RELATION_HAS_ONE,
					"storageProperty" => "_edForm",
					"storageField" => "study_form_id",
					"managerClass" => "CTaxonomyManager",
					"managerGetObject" => "getEductionForm"
            ),
    		"scienceSpec" => array(
					"relationPower" => RELATION_HAS_ONE,
					"storageProperty" => "_scienceSpec",
					"storageField" => "science_spec_id",
					"managerClass" => "CTaxonomyManager",
					"managerGetObject" => "getScienceSpeciality"
    		)
    	);
    }
    public function attributeLabels() {
    	return array(
    			"kadri_id" => "ФИО",
    			"person.fio" => "ФИО",
    			"science_spec_id" => "Номер спец-ти",
    			"study_form_id" => "Форма обучения",
    			"scinceMan" => "Руководитель",
    			"tema" => "Тема",
    			"god_zach" => "Год защиты",
    			"date_end" => "Дата окончания",
    			"comment" => "Комментарий"
    	);
    }

}