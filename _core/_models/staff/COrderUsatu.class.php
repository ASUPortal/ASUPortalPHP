<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 18:58
 * To change this template use File | Settings | File Templates.
 */
class COrderUsatu extends CActiveModel {
    protected $_table = TABLE_USATU_ORDERS;
    protected $_type = null;
    protected function relations() {
        return array(
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_type",
                "storageField" => "orders_type",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getUsatuOrderType"
            ),
        );
    }
}
