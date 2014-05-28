<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.05.14
 * Time: 20:42
 * To change this template use File | Settings | File Templates.
 */

class CNMSProtocolAgendaPoint extends CActiveModel {
    protected $_table = TABLE_NMS_PROTOCOL_AGENDA;

    public $protocol_id;
    public $section_id;

    protected $_protocol = null;
    protected $_members = null;
    protected $_opinion = null;

    public function relations() {
        return array(
            "protocol" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_protocol",
                "storageField" => "protocol_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getNMSProtocol"
            ),
            "members" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_members",
                "joinTable" => TABLE_NMS_PROTOCOL_AGENDA_MEMBERS,
                "leftCondition" => "agenda_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "person_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "opinion" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_opinion",
                "storageField" => "opinion_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getProtocolOpinion"
            ),
        );
    }

    /**
     * Кого слушали
     *
     * @return string
     */
    public function getMembersAsString() {
        $members = array();
        foreach ($this->members->getItems() as $person) {
            $members[] = $person->getName();
        }
        return implode(", ", $members);
    }
}