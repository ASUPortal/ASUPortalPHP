<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.05.14
 * Time: 20:02
 * To change this template use File | Settings | File Templates.
 */

class CNMSProtocol extends CActiveModel{
    protected $_table = TABLE_NMS_PROTOCOL;

    protected $_agenda = null;

    public function relations() {
        return array(
            "agenda" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_agenda",
                "storageTable" => TABLE_NMS_PROTOCOL_AGENDA,
                "storageCondition" => "protocol_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getNMSProtocolAgendaPoint"
            ),
        );
    }
    public function attributeLabels() {
    	return array(
    			"corriculum_speciality_direction_id" => "Направление подготовки"
    	);
    }
    public function fieldsProperty() {
        return array(
            'original' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS."library".CORE_DS."protocols".CORE_DS
            )
        );
    }
}