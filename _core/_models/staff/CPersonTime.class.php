<?php

class CPersonTime extends CPerson {
    protected $_table = TABLE_PERSON;
    protected $_orders = null;
        
    public function relations() {
        return array(
        	"orders" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageProperty" => "_orders",
        		"storageTable" => TABLE_STAFF_ORDERS,
        		"storageCondition" => "kadri_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND order_active=1",
        		"managerClass" => "CStaffManager",
        		"managerGetObject" => "getOrder"
        	)
        );
    }
    public function attributeLabels() {
        return array(
            "fio_short" => "ФИО преподавателя",
        	"dolgnost" => "Должность",
            "stavka" => "Ставка план"
        );
    }
    public function getPost() {
    	if (is_null($this->_post)) {
    		$term = CTaxonomyManager::getPostById($this->getPostId());
    		if (!is_null($term)) {
    			$this->_post = $term;
    		}
    	}
    	return $this->_post;
    }
    public function getPostId() {
    	return $this->getRecord()->getItemValue("dolgnost");
    }
    public function getActiveOrders() {
    	$result = new CArrayList();
    	foreach ($this->orders->getItems() as $order) {
    		if ($order->isActive()) {
    			$result->add($order->getId(), $order);
    		}
    	}
    	return $result;
    }
    public function getOrdersRate() {
    	$result = 0;
    	foreach ($this->getActiveOrders()->getItems() as $order) {
    		$result += $order->rate;
    	}
    	return $result;
    }
    public function getOrdersCount() {
    	$cnt = $this->getActiveOrders()->getCount();
    	return $cnt;
    }
}