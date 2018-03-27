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
            "personal_user" => "Личная ссылка пользователя"
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
     * Получить ссылку на элемент рабочего стола с учётом текущего пользователя или сотрудника
     * 
     * @return string
     */
    public function getLink() {
        if ($this->personal_user) {
            return $this->link.CSession::getCurrentUser()->getId();
        } elseif ($this->personal_staff) {
            return $this->link.CSession::getCurrentPerson()->getId();
        } else {
            return $this->link;
        }
    }
}