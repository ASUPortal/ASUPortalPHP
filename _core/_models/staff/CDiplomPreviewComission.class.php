<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 25.02.13
 * Time: 17:45
 * To change this template use File | Settings | File Templates.
 */

class CDiplomPreviewComission extends CActiveModel {
	protected $_table = TABLE_DIPLOM_PREVIEW_COMISSIONS;
    protected $_secretar = null;
    protected $_members = null;
    

	public function relations() {
		return array(
				"secretar" => array(
					"relationPower" => RELATION_HAS_ONE,
					"storageProperty" => "_secretar",
					"storageField" => "secretary_id",
					"managerClass" => "CStaffManager",
					"managerGetObject" => "getPerson"
				),
				"members" => array(
						"relationPower" => RELATION_MANY_TO_MANY,
						"storageProperty" => "_members",
						"joinTable" => TABLE_DIPLOM_PREVIEW_MEMBERS,
						"leftCondition" => "comm_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
						"rightKey" => "kadri_id",
						"managerClass" => "CStaffManager",
						"managerGetObject" => "getPerson"
				),
		);
	}
	public function attributeLabels() {
		return array(
				"secretary_id" => "Секретарь комиссии",
    			"name" => "Имя/Номер комиссии",
    			"date_act" => "Дата создания комиссии",
    			"comment" => "Примечание",
				"members" => "Члены комиссии"
		);
	}
	public function validationRules() {
		return array(
				"required" => array(
						"name"
				)
		);
	}
	public function fieldsProperty() {
		return array(
				"date_act" => array(
						"type" => FIELD_MYSQL_DATE,
						"format" => "d.m.Y"
				)
		);
	}
	public function remove() {
		foreach (CActiveRecordProvider::getWithCondition(TABLE_DIPLOM_PREVIEW_MEMBERS, "comm_id=".$this->getId())->getItems() as $ar) {
			$ar->remove();
		}
		parent::remove();
	}
}

