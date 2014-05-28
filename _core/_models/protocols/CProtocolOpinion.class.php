<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.05.14
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */

class CProtocolOpinion extends CActiveModel{
    protected $_table = TABLE_PROTOCOL_OPINIONS;

    protected $_opinion = null;

    public function relations() {
        return array(
            "opinion" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_opinion",
                "storageField" => "opinion_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getProtocolOpinion"
            )
        );
    }

    public function getValue() {
        return $this->name;
    }
}