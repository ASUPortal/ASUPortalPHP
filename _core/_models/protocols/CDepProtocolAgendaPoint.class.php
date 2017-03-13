<?php
/**
 * Пункты протоколов кафедры
 *
 */
class CDepProtocolAgendaPoint extends CActiveModel {
    protected $_table = TABLE_DEP_PROTOCOL_AGENDA;
    protected $_protocol = null;
    protected $_decision = null;
    public $_person;

    public function relations() {
        return array(
            "protocol" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_protocol",
                "storageField" => "_protocol_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
            ),
            "decision" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_decision",
                "storageField" => "opinion_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getProtocolOpinion"
            ),
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "kadri_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            )
        );
    }
    
    public function attributeLabels() {
    	return array(
    			"section_id" => "Номер пункта протокола",
    			"kadri_id" => "Слушали (автор доклада)",
    			"text_content" => "Текст доклада",
    			"opinion_id" => "Постановили",
    			"on_control" => "Отметка о контроле",
    			"opinion_text" => "Дополнение к решению"
    	);
    }
    
    protected function validationRules() {
    	return array(
    		"required" => array(
    			"protocol_id",
    			"kadri_id"
    		)
    	);
    }
}