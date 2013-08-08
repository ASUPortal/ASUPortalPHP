<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.08.13
 * Time: 11:57
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanPersonChange extends CActiveModel{
    protected $_table = TABLE_IND_PLAN_CHANGES;

    public $id_kadri;
    public $zav;
    public $prep;
    public $id_otmetka = 0;

    public function __construct(CActiveRecord $aRecord = null) {
        parent::__construct($aRecord);

        $this->zav = date("d.m.Y");
        $this->prep = date("d.m.Y");
    }

    /**
     * @return string
     */
    public function getMark() {
        if ($this->id_otmetka == 1) {
            return "Да";
        }
        return "Нет";
    }
}