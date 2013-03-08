<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 31.07.12
 * Time: 21:42
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumDisciplineLabor extends CActiveModel {
    protected $_form = null;
    protected $_type = null;

    protected function relations() {
        return array(
            "form" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_form",
                "storageField" => "form_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_type",
                "storageField" => "type_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
        );
    }
}
