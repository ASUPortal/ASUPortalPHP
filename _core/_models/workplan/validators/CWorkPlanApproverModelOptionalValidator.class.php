<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 11.12.15
 * Time: 23:23
 */

class CWorkPlanApproverModelOptionalValidator extends  IModelValidatorOptional{
    public function getError() {
        return "Скоро здесь будут информационные сообщения";
    }

    function onRead(CModel $model) {
        return false;
    }

}