<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 17:21
 * To change this template use File | Settings | File Templates.
 */

class CLibraryManager {
    private static $_cacheDocuments = null;
    private static $_cacheFiles = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheDocuments() {
        if (is_null(self::$_cacheDocuments)) {
            self::$_cacheDocuments = new CArrayList();
        }
        return self::$_cacheDocuments;
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
     * @param $key
     * @return CLibraryFile
     */
    public static function getFile($key) {
        if (!self::getCacheFiles()->hasElement($key)) {
        	foreach (CActiveRecordProvider::getWithCondition(TABLE_LIBRARY_FILES, "id='".$key."'")->getItems() as $item) {
        		$file = new CLibraryFile($item);
        		self::getCacheFiles()->add($file->getId(), $file);
        	}
        }
        return self::getCacheFiles()->getItem($key);
    }

    /**
     * @param $key
     * @return CArrayList
     */
    public static function getFilesByFolder($key) {
        $result = new CArrayList();
        $query = new CQuery();
        $query->select("*")
            ->from(TABLE_LIBRARY_FILES)
            ->condition('nameFolder = "'.$key.'"')
        	->order("browserFile asc");
        foreach ($query->execute()->getItems() as $data) {
            $file = new CLibraryFile(new CActiveRecord($data));
            $result->add($file->getId(), $file);
        }
        return $result;
    }

    /**
     * @param $key
     * @return CLibraryDocument
     */
    public static function getDocument($key) {
        if (!self::getCacheDocuments()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_LIBRARY_DOCUMENTS, $key);
            if (!is_null($ar)) {
                $doc = new CLibraryDocument($ar);
                self::getCacheDocuments()->add($doc->getId(), $doc);
                self::getCacheDocuments()->add("folder_".$doc->getFolderId(), $doc);
            }
        }
        return self::getCacheDocuments()->getItem($key);
    }

    /**
     * @param $key
     * @return CLibraryDocument
     */
    public static function getDocumentByFolderId($key) {
        $storageKey = "folder_".$key;
        if (!self::getCacheDocuments()->hasElement($storageKey)) {
            foreach (CActiveRecordProvider::getWithCondition(TABLE_LIBRARY_DOCUMENTS, "nameFolder = '".$key."'")->getItems() as $ar) {
                $doc = new CLibraryDocument($ar);
                self::getCacheDocuments()->add($doc->getId(), $doc);
                self::getCacheDocuments()->add("folder_".$doc->getFolderId(), $doc);
            }
        }
        return self::getCacheDocuments()->getItem($storageKey);
    }

    /**
     * Последние добавленные документы
     *
     * @return CArrayList
     */
    public static function getLatestDocuments() {
        $latest = new CArrayList();
        $query = new CQuery();
        $query->select("file.*")
            ->from(TABLE_LIBRARY_FILES." as file")
            ->condition("user_id != 0")
            ->order("file.id desc")
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $data) {
            $doc = new CLibraryFile(new CActiveRecord($data));
            $latest->add($doc->getId(), $doc);
        }
        return $latest;
    }

    /**
     * Буквы, на которые есть дисциплины, для которых есть материалы
     *
     * @return CArrayList
     */
    public static function getSubjectAlphabetically() {
        $result = new CArrayList();
        $query = new CQuery();
        $query->select("DISTINCT LEFT(subject.name, 1) as subj_name, ORD(LEFT(subject.name, 1)) as code")
            ->from(TABLE_DISCIPLINES." as subject")
            ->innerJoin(TABLE_LIBRARY_DOCUMENTS." as doc", "doc.subj_id = subject.id")
            ->innerJoin(TABLE_LIBRARY_FILES." as file", "doc.nameFolder = file.nameFolder")
            ->order("subject.name");
        foreach ($query->execute()->getItems() as $data) {
            $result->add($data["code"], $data["subj_name"]);
        }
        return $result;
    }
    
    /**
     * Документ с учебными материалами по пользователю и дисциплине
     * 
     * @param CUser $user
     * @param CDiscipline $discipline
     * @return CLibraryDocument
     */
    public static function getLibraryDocumentByUserAndDiscipline(CUser $user, CTerm $discipline) {
        $documents = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_LIBRARY_DOCUMENTS, "user_id = '".$user->getId()."' and subj_id = '".$discipline->getId()."'")->getItems() as $ar) {
            $document = new CLibraryDocument($ar);
            $documents->add($document->getId(), $document);
        }
        return $documents->getFirstItem();
    }
}