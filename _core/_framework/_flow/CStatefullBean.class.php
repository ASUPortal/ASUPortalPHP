<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Aleksandr Barmin
 * Date: 12.10.14
 * Time: 18:24
 * 
 * URL: http://mydesignstudio.ru/
 * mailto: abarmin@mydesignstudio.ru
 * twitter: @alexbarmin
 */

class CStatefullBean extends CArrayList{
    private $_beanId = "";

    public function __construct() {
        $this->_beanId = md5(time());
        parent::__construct();
    }


    /**
     * @return string
     */
    public function getBeanId() {
        return $this->_beanId;
    }
}