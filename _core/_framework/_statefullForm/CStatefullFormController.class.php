<?php

/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.11.15
 * Time: 11:25
 */
define("FORM_BEAN", "formBeanId");

abstract class CStatefullFormController extends CBaseController{
    /**
     * CStatefullFormController constructor.
     */
    public function __construct() {
        // последний шаг - запускаем рендеринг
        $this->render();
    }


    /**
     * @return CStatefullFormBean
     */
    protected function getStatefullFormBean() {
        if (CRequest::getString(FORM_BEAN) != "") {
            return CApp::getApp()->beans->getStatefullBean(CRequest::getString(FORM_BEAN));
        }
        return new CStatefullFormBean();
    }

    abstract function render();
}