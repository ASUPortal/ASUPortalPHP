<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:50
 */

abstract class CHtmlPrintTemplateField implements IPrintTemplateField {
    protected $_field;
    
    /**
     * CHtmlPrintTemplateField constructor
     * @param CPrintField $field
     */
    public function __construct(CPrintField $field) {
        $this->_field = $field;
    }
    
}