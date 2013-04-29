<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 29.04.13
 * Time: 21:08
 * To change this template use File | Settings | File Templates.
 */

class CGrantEvent extends CActiveModel {
    protected $_table = TABLE_GRANT_EVENTS;

    /**
     * @return string
     */
    public function getTiming() {
        $result = "";
        if ($this->date_start !== "") {
            $result .= "с ".date("d.m.Y", strtotime($this->date_start));
        }
        if ($this->date_end !== "") {
            $result .= " по ".date("d.m.Y", strtotime($this->date_end));
        }
        return $result;
    }
}