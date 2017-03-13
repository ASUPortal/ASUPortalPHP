<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.06.12
 * Time: 13:20
 * To change this template use File | Settings | File Templates.
 *
 * Протокол заседания кафедры
 */
class CDepartmentProtocol extends CActiveModel {
	protected $_table = TABLE_DEPARTMENT_PROTOCOLS;
	protected $_control = null;
	protected $_agenda = null;
	protected $_visits = null;
	
	protected function relations() {
		return array(
			"control" => array(
					"relationPower" => RELATION_HAS_MANY,
					"storageProperty" => "_agenda",
					"storageTable" => TABLE_DEP_PROTOCOL_AGENDA,
					"storageCondition" => "protocol_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND on_control=1",
					"targetClass" => "CDepProtocolAgendaPoint"
			),
			"agenda" => array(
					"relationPower" => RELATION_HAS_MANY,
					"storageProperty" => "_agenda",
					"storageTable" => TABLE_DEP_PROTOCOL_AGENDA,
					"storageCondition" => "protocol_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
					"targetClass" => "CDepProtocolAgendaPoint",
					"managerOrder" => "`section_id` asc"
			),
			"visits" => array(
					"relationPower" => RELATION_HAS_MANY,
					"storageProperty" => "_visits",
					"storageTable" => TABLE_DEP_PROTOCOL_VISIT,
					"storageCondition" => "protocol_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
					"targetClass" => "CDepProtocolVisit"
			)
		);
	}
	
	public function attributeLabels() {
		return array(
			"date_text" => "Дата",
			"program_content" => "Текст повестки",
			"num" => "Номер",
			"comment" => "Комментарий"
		);
	}
	
	protected function validationRules() {
		return array(
			"required" => array(
				"date_text",
				"num",
				"program_content"
			)
		);
	}
	
	public function fieldsProperty() {
		return array(
			"date_text" => array(
				"type" => FIELD_MYSQL_DATE,
				"format" => "d.m.Y"
			)
		);
	}
	
	public function getName() {
		return "№".$this->num." от ".date("d.m.Y", strtotime($this->date_text));
	}
	
    public function getNumber() {
        return $this->getRecord()->getItemValue("num");
    }
    
    public function getDate() {
        return $this->getRecord()->getItemValue("date_text");
    }
}
