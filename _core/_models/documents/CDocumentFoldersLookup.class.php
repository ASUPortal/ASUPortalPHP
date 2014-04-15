<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.04.14
 * Time: 15:28
 */

class CDocumentFoldersLookup implements ISearchCatalogInterface{

    public function actionTypeAhead($lookup)
    {
        $result = array();
        // выбор папок
        $query = new CQuery();
        $query->select("folder.id as id, folder.title as name")
            ->from(TABLE_DOCUMENT_FOLDERS." as folder")
            ->condition("folder.title like '%".$lookup."%'")
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        if ($id == 0) {
            return array(
                "0" => "Корневая папка"
            );
        }
        $folder = CDocumentsManager::getFolder($id);
        if (!is_null($folder)) {
            return array(
                $folder->getId() => $folder->title
            );
        }
    }

    public function actionGetViewData()
    {
        $result = array();
        $result[0] = "Корневая папка";
        $this->fillFoldersHierarchy($result);
        return $result;
    }

    private function fillFoldersHierarchy(&$arr) {
        foreach (CDocumentsManager::getFoldersTopLevel()->getItems() as $folder) {
            $arr[$folder->getId()] = $folder->title;
            $this->fillSubfolders($folder, $arr, 1);
        }
    }
    private function fillSubfolders(CDocumentFolder $folder, &$arr, $level) {
        foreach ($folder->getChildFolders()->getItems() as $subfolder) {
            $arr[$subfolder->getId()] = str_repeat("-", $level)." ".$subfolder->title;
            $this->fillSubfolders($subfolder, $arr, $level++);
        }
    }

    public function actionGetCreationActionUrl()
    {
        // TODO: Implement actionGetCreationActionUrl() method.
    }
}