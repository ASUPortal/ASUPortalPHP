<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:40
 */

class CHtmlPrintTemplate implements IPrintTemplate {
    private $form;

    function __construct($form) {
        $this->form = $form;
    }


}