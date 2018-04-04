<?php
class CDashboardItem extends CActiveModel {
	protected $_children = null;
	protected $_table = TABLE_DASHBOARD;
	public static function getClassName() {
		return __CLASS__;
	}
	public function relations() {
		return array(
			"children" => array(
				"relationPower" => RELATION_HAS_MANY,
				"storageProperty" => "_children",
				"storageTable" => TABLE_DASHBOARD,
				"storageCondition" => "parent_id = " . $this->id,
				"managerClass" => "CDashboardManager",
				"managerGetObject" => "getDashboardItem"
			),
			"user_group" => array(
				"relationPower" => RELATION_HAS_ONE,
				"storageProperty" => "_user_group",
				"storageField" => "group_id",
				"managerClass" => "CStaffManager",
				"managerGetObject" => "getUserGroup"
			)			
		);
	}
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "link" => "Ссылка",
            "icon" => "Значок",
            "parent_id" => "Родительский элемент",
            "group_id" => "Группа пользователей, в которой показывать элемент",
            "personal_staff" => "Личная ссылка сотрудника",
            "personal_user" => "Личная ссылка пользователя",
            "current_year" => "Учитывать текущий год",
            "year_addition" => "Год в дополнение к ссылке",
            "year_link" => "Ссылка на год в адресе"
        );
    }
    public function addChild(CDashboardItem $child = null) {
        if (is_null($this->_children)) {
            $this->_children = new CArrayList();
        }
        if (!is_null($child)) {
            $this->_children->add($child->getId(), $child);
        }
    }
    /**
     * Получить ссылку на элемент рабочего стола с учётом текущего пользователя или сотрудника,
     * а также текущего или указанного года
     * 
     * @return string
     */
    public function getLink() {
        $link = "";
        if ($this->personal_user) {
            $link = $this->link.CSession::getCurrentUser()->getId();
        } elseif ($this->personal_staff) {
            $link = $this->link.CSession::getCurrentPerson()->getId();
        } else {
            $link = $this->link;
        }
        if ($this->current_year) {
            $link .= "&".$this->year_link."=".CUtils::getCurrentYear()->getId();
        } elseif ($this->year_addition != 0) {
            $link .= "&".$this->year_link."=".$this->year_addition;
        }
        return $link;
    }
}