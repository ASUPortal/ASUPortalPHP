<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 11.06.12
 * Time: 17:22
 * To change this template use File | Settings | File Templates.
 */
class CUserRole extends CActiveModel {
    public function getName() {
        return $this->getRecord()->getItemValue("name");
    }
}
