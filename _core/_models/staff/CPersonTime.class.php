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
    /**
     * Список приказов с номером, датой и ставкой
     * 
     * @return array
     */
    public function getActiveOrdersListWithRate() {
    	$result = array();
    	foreach ($this->getActiveOrders()->getItems() as $order) {
    		$typeMoney = "";
    		if ($order->type_money == 2) {
    			$typeMoney = "Б";
    		} elseif ($order->type_money == 3) {
    			$typeMoney = "К";
    		}
    		$result[$order->getId()] = "Приказ №".$order->num_order." от ".$order->date_order." (".$order->rate.") ".$typeMoney;
    	}
    	return $result;
    }
}