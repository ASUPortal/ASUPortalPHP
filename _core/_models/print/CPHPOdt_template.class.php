<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.03.13
 * Time: 18:36
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class CPHPOdt_template
 * @deprecated
 */
class CPHPOdt_template extends CAbstractDocumentTemplate{
    private $_documentXML = null;
    private $_styleXML = null;
    private $_xmlDocument = null;
    private $_xmlStyle = null;

    public function __construct($file) {
        $path = dirname($file);
        $this->_tempFileName = $path.DIRECTORY_SEPARATOR.time().'.odt';

        copy($file, $this->_tempFileName); // Copy the source File to the temp File

        $this->_objZip = new ZipArchive();
        $this->_objZip->open($this->_tempFileName);

        $this->_documentXML = $this->_objZip->getFromName('content.xml');
        $this->_styleXML = $this->_objZip->getFromName('styles.xml');
    }

    function setValue($field, $value)
    {
        // TODO: Implement setValue() method.
    }

    function save($filename) {

    }

    /**
     * Содержимое файла в виде строки
     *
     * @param $xmlString
     */
    public function setDocXML($xmlString) {
        $this->_documentXML = $xmlString;
    }
    /**
     * Содержимое стилей в виде строки
     * 
     * @param unknown $xmlString
     */
    public function setStyleXML($xmlString) {
    	$this->_styleXML = $xmlString;
    }




}
