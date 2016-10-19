<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.03.13
 * Time: 18:37
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class CAbstractDocumentTemplate
 * @deprecated
 */
abstract class CAbstractDocumentTemplate {
    abstract function setValue($field, $value);
    abstract function save($filename);
    abstract function getDocXML();
    abstract function getFields();
}
