<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.03.13
 * Time: 18:36
 * To change this template use File | Settings | File Templates.
 */
class CPHPOdt_template extends CAbstractDocumentTemplate{
    private $_documentXML = null;
    private $_xmlDocument = null;

    public function __construct($file) {
        $path = dirname($file);
        $this->_tempFileName = $path.DIRECTORY_SEPARATOR.time().'.odt';

        copy($file, $this->_tempFileName); // Copy the source File to the temp File

        $this->_objZip = new ZipArchive();
        $this->_objZip->open($this->_tempFileName);

        $this->_documentXML = $this->_objZip->getFromName('content.xml');
    }

    function setValue($field, $value)
    {
        // TODO: Implement setValue() method.
    }

    function save($filename) {
        if(file_exists($filename)) {
            unlink($filename);
        }

        $this->_objZip->addFromString('content.xml', $this->_documentXML);

        // Close zip file
        if($this->_objZip->close() === false) {
            throw new Exception('Could not close zip file.');
        }

        rename($this->_tempFileName, $filename);
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
     * Текст xml-документа, который лежит в основе
     *
     * @return mixed|null
     */
    function getDocXML() {
        return $this->_documentXML;
    }

    /**
     * Все поля, которые есть в документе
     *
     * @return array
     */
    function getFields() {
        $fields = array();
        $nodes = $this->getXMLDocument()->getElementsByTagNameNS("urn:oasis:names:tc:opendocument:xmlns:text:1.0", "user-field-get");
        foreach ($nodes as $node) {
            $fields[$node->textContent] = $node;
        }
        return $fields;
    }

    /**
     * XML в виде объекта DOMDocument
     *
     * @return DOMDocument|null
     */
    private function getXMLDocument() {
        if (is_null($this->_xmlDocument)) {
            $doc = new DOMDocument();
            $doc->loadXML($this->getDocXML());
            $this->_xmlDocument = $doc;
        }
        return $this->_xmlDocument;
    }
}
