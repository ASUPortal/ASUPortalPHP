<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:40
 */

/**
 * Class CHtmlPrintTemplate
 *
 * @property CPrintForm form
 */
class CHtmlPrintTemplate implements IPrintTemplate {
    private $form;
    private $file;
    private $_temporaryFileName;

    function __construct($form) {
        $this->form = $form;
        //
        $file = PRINT_TEMPLATES_DIR.$form->template_file;
        $this->file = $file;
        $path = dirname($file);
        $this->_temporaryFileName = $path.DIRECTORY_SEPARATOR.time().'.html';
        // Copy the source File to the temp File
        copy($file, $this->_temporaryFileName);
    }

    public function getFields() {
        $fields = [];
        // это все обычные поля
        $formsetFields = $this->form->formset->fields;
        // открываем html как xml-документ
        $document = new DOMDocument();
        $document->loadHTMLFile($this->_temporaryFileName);
        /**
         * @var CPrintField $field
         */
        foreach ($formsetFields as $field) {
            $dom = $this->lookupFieldByName($field->alias);
            if (!is_null($dom)) {
                if ($field->type_id == IPrintClassField::FIELD_TEXT) {
                    $fields[] = new CHtmlTextPrintTemplateField($dom, $field);
                } else if ($field->type_id == IPrintClassField::FIELD_TABLE) {
                    $fields[] = new CHtmlTablePrintTemplateField($dom, $field);
                } else {
                    throw new Exception("Unsupported field type" . $field->type_id);
                }
            }
        }
        return $fields;
    }

    /**
     * @param String $fieldName
     * @param DOMNode $domNode
     * @return mixed
     */
    private function lookupFieldByName($fieldName, $domNode) {
        if ($domNode->textContent == $fieldName) {
            return $domNode;
        } else {
            $childNodes = $domNode->childNodes;
            for ($i = 0; $i < $childNodes->length; $i++) {
                $childNode = $childNodes->item($i);
                $childValue = $this->lookupFieldByName($fieldName, $childNode);
                if (!is_null($childValue)) {
                    return $childNode;
                }
            }
            return null;
        }
    }
}