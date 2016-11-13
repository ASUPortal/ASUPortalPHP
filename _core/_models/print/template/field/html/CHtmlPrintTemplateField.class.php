<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:50
 */

abstract class CHtmlPrintTemplateField implements IPrintTemplateField {
    protected $_domNode;
    protected $_field;

    /**
     * CHtmlPrintTemplateField constructor.
     * @param $_domNode
     * @param $_field
     */
    public function __construct(DOMNode $_domNode, CPrintField $_field) {
        $this->_domNode = $_domNode;
        $this->_field = $_field;
    }


}