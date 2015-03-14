<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.03.15
 * Time: 21:54
 */

class CWorkPlan extends CActiveModel implements IJSONSerializable{
    protected $_table = TABLE_WORK_PLANS;

    public function toJsonObject()
    {
        $obj = new stdClass();
        $obj->id = $this->getId();
        $obj->title = $this->title;

        return $obj;
    }
}