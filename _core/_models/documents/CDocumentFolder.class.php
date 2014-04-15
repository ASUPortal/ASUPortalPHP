<?php
class CDocumentFolder extends CActiveModel {
    protected $_table = TABLE_DOCUMENT_FOLDERS;
    private $_childFolders = null;

    public function isFolder() {
        return true;
    }

    /**
     * @return CArrayList|null
     */
    public function getChildFolders() {
        if (is_null($this->_childFolders)) {
            $this->_childFolders = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_DOCUMENT_FOLDERS, "parent_id=".$this->getId())->getItems() as $ar) {
                $folder = new CDocumentFolder($ar);
                $this->_childFolders->add($folder->getId(), $folder);
            }
        }
        return $this->_childFolders;
    }
}