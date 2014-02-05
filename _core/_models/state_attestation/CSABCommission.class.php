<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.04.13
 * Time: 12:41
 * To change this template use File | Settings | File Templates.
 */

class CSABCommission extends CActiveModel {
    protected $_table = TABLE_SAB_COMMISSIONS;
    protected $_members = null;
    protected $_students = null;
    protected $_manager = null;
    protected $_secretar = null;
    protected $_diploms = null;
    protected $_year = null;

    public function relations() {
        return array(
            "members" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_members",
                "joinTable" => TABLE_SAB_COMMISSION_MEMBERS,
                "leftCondition" => "commission_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "person_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "diploms" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_diploms",
                "storageTable" => TABLE_DIPLOMS,
                "storageCondition" => "gak_num = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getDiplom",
                "managerOrder" => "date_act asc"
            ),
            "manager" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_manager",
                "storageField" => "manager_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "secretar" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_secretar",
                "storageField" => "secretar_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "year" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_year",
                "storageField" => "year_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getYear"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "comment" => "Описание",
            "year_id" => "Учебный год",
            "secretar_id" => "Секретарь",
            "manager_id" => "Председатель комиссии",
            "members" => "Члены комиссии",
            "order_id" => "Приказ по комиссии"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title"
            )
        );
    }
    public function remove() {
        foreach (CActiveRecordProvider::getWithCondition(TABLE_SAB_COMMISSION_MEMBERS, "commission_id=".$this->getId())->getItems() as $ar) {
            $ar->remove();
        }
        parent::remove();
    }
    public function save() {
        parent::save();
        /**
         * У сотрудников, которых нет приказов по
         * ГАК в году комиссии автоматически устанавливается
         * приказ от комиссии
         */
        if ($this->order_id !== 0) {
            $persons = new CArrayList();
            foreach ($this->members->getItems() as $person) {
                $persons->add($person->getId(), $person);
            }
            if (!is_null($this->manager)) {
                $persons->add($this->manager->getId(), $this->manager);
            }
            foreach ($persons->getItems() as $person) {
                if (is_null($person->getSABOrdersByYear($this->year))) {
                    $ar = new CActiveRecord(array(
                        "id" => null,
                        "person_id" => $person->getId(),
                        "year_id" => $this->year->getId(),
                        "order_id" => $this->order_id
                    ));
                    $ar->setTable(TABLE_SAB_PERSON_ORDERS);
                    $ar->insert();
                }
            }
        }
    }
    public function getDiplomsListByDate() {
		$result = array();
		foreach ($this->diploms->getItems() as $diplom) {
			$byDay = array();
			if (array_key_exists($diplom->date_act, $result)) {
				$byDay = $result[$diplom->date_act];
			}
			$byDay[] = $diplom;
			$result[$diplom->date_act] = $byDay;
		}
		return $result;
	}
    public function getStatByEducationTypeByDate() {
        $result = array();
        foreach ($this->diploms->getItems() as $diplom) {
            $byDay = array();
            if (array_key_exists($diplom->date_act, $result)) {
                $byDay = $result[$diplom->date_act];
            }
            $student = $diplom->student;
            if (!is_null($student)) {
                $type = $student->secondaryEducationEndType;
                if (!is_null($type)) {
                    $type = $type->getValue();
                } else {
                    $type = "не указано";
                }
                $byType = array();
                if (array_key_exists($type, $byDay)) {
                    $byType = $byDay[$type];
                }
                $byType[] = $student->getName();
                $byDay[$type] = $byType;
            }
            $result[$diplom->date_act] = $byDay;
        }
        return $result;
    }
}
