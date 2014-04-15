<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.04.14
 * Time: 14:54
 */

class CDocumentsManager {
    private static $_cacheFolders = null;
    private static $_cacheFiles = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheFolders() {
        if (is_null(self::$_cacheFolders)) {
            self::$_cacheFolders = new CArrayList();
        }
        return self::$_cacheFolders;
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheFiles() {
        if (is_null(self::$_cacheFiles)) {
            self::$_cacheFiles = new CArrayList();
        }
        return self::$_cacheFiles;
    }

    /**
     * @param int $key
     * @return CDocumentFolder
     */
    public static function getFolder($key = 0) {
        if (!self::getCacheFolders()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_DOCUMENT_FOLDERS, $key);
            if (!is_null($ar)) {
                $obj = new CDocumentFolder($ar);
                self::getCacheFolders()->add($key, $obj);
            }
        }
        return self::getCacheFolders()->getItem($key);
    }

    /**
     * @param int $key
     * @return CDocumentFile
     */
    public static function getFile($key = 0) {
        if (!self::getCacheFiles()->hasElement($key)) {
            foreach (CActiveRecordProvider::getWithCondition(TABLE_DOCUMENTS, "id_file=".$key)->getItems() as $ar) {
                $dar = new CDocumentActiveRecord($ar->getItems());
                $obj = new CDocumentFile($dar);
                self::getCacheFiles()->add($obj->getId(), $obj);
            }
        }
        return self::getCacheFiles()->getItem($key);
    }

} 