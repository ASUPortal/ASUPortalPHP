<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.06.12
 * Time: 13:20
 * To change this template use File | Settings | File Templates.
 *
 * Протокол заседния кафедры
 */
class CDepartmentProtocol extends CActiveModel {
    public function getNumber() {
        return $this->getRecord()->getItemValue("num");
    }
    public function getDate() {
        return $this->getRecord()->getItemValue("date_text");
    }
}
