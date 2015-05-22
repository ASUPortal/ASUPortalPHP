<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 25.02.13
 * Time: 13:48
 * To change this template use File | Settings | File Templates.
 */
class CPracticePlace extends CTerm {
	protected $_table = TABLE_PRACTICE_PLACES;
	private $_town = null;
	
	public function relations() {
		return array(
				"town" => array(
						"relationPower" => RELATION_HAS_ONE,
						"storageProperty" => "_town",
						"storageField" => "town_id",
						"managerClass" => "CTaxonomyManager",
						"managerGetObject" => "getTown"
				),
				"diploms" => array(
						"relationPower" => RELATION_HAS_MANY,
						"storageProperty" => "_diploms",
						"storageTable" => TABLE_DIPLOMS,
						"storageCondition" => "pract_place_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
						"managerClass" => "CStaffManager",
						"managerGetObject" => "getDiplom"
				)
		);
	}
	public function attributeLabels() {
		return array(
				"name" => "Наименование",
				"town_id" => "Город",
				"towns.name" => "Город",
				"comment" => "Комментарий"
		);
	}
	public function validationRules() {
		return array(
				"required" => array(
						"name"
				)
		);
	}
	public function getDiploms() {
		$result = new CArrayList();
		foreach ($this->diploms->getItems() as $diplom) {
			$result->add($diplom->getId(), $diplom);
		}
		return $result;
	}
	public function getDiplomsCount() {
		$result = $this->getDiploms()->getCount();
		return $result;
	}
}
