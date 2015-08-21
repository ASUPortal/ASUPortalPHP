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
            "user.FIO" => "Пользователь"
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
}