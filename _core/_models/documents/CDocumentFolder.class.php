<?php
class CDocumentFolder extends CActiveModel {
    protected $_table = TABLE_DOCUMENT_FOLDERS;

    public function isFolder() {
        return true;
    }
}