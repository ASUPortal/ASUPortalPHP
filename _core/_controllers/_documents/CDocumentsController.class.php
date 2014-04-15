<?php
class CDocumentsController extends CBaseController{
    protected $allowedAnonymous = array(
        "index"
    );
    public function __construct() {
        if (!CSession::isAuth()) {
            $action = CRequest::getString("action");
            if ($action == "") {
                $action = "index";
            }
            if (!in_array($action, $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление нормативными документами");

        parent::__construct();
    }
    public function actionIndex() {
        $parent = 0;
        $title = "Нормативные документы";
        if (CRequest::getInt("parent")) {
            $parent = CRequest::getInt("parent");
            $parentFolder = CDocumentsManager::getFolder($parent);
            if (!is_null($parentFolder)) {
                $title = $parentFolder->title;
            }
        }
        // извлекаем все папки с учетом иерархии
        $query = new CQuery();
        $query->select("f.*")
            ->from(TABLE_DOCUMENT_FOLDERS." as f")
            ->condition("f.parent_id = ".$parent)
            ->order("f.title asc");
        $objects = new CArrayList();
        foreach ($query->execute()->getItems() as $ar) {
            $folder = new CDocumentFolder(new CActiveRecord($ar));
            $objects->add($objects->getCount(), $folder);
        }
        // теперь извлекаем все файлы
        $query = new CQuery();
        $query->select("f.*")
            ->from(TABLE_DOCUMENTS." as f")
            ->condition("f.folder_id = ".$parent." and f.nameFolder like 'gost%'")
            ->order("f.browserFile asc");
        foreach ($query->execute()->getItems() as $ar) {
            $file = new CDocumentFile(new CDocumentActiveRecord($ar));
            $objects->add($objects->getCount(), $file);
        }
        $this->setData("title", $title);
        $this->setData("objects", $objects);
        /**
         * Генерация меню
         */
        // если есть родительская папка, то переходим в нее
        if ($parent != 0) {
            $parentFolder = CDocumentsManager::getFolder($parent);
            if (!is_null($parentFolder)) {
                $this->addActionsMenuItem(array(
                    "title" => "Назад",
                    "link" => "index.php?action=index&parent=".$parentFolder->parent_id,
                    "icon" => "actions/edit-undo.png"
                ));
            }
        }
        // если пользователь может чего-нибудь добавлять, то пусть добавит
        $this->setData("canEdit", false);
        if (CSession::isAuth()) {
            if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_ALL ||
                CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {

                $this->addActionsMenuItem(array(
                    "title" => "Создать папку",
                    "link" => "index.php?action=add&parent=".$parent,
                    "icon" => "actions/folder-new.png"
                ));

                $this->addActionsMenuItem(array(
                    "title" => "Загрузить файл",
                    "link" => "files.php?action=add&parent=".$parent,
                    "icon" => "actions/bookmark-new.png"
                ));

                $this->setData("canEdit", true);
            }
        }
        /**
         * Отображение представления
         */
        $this->addCSSInclude("_modules/_documents/style.css");
        $this->renderView("_documents/_folder/index.tpl");
    }
    public function actionAdd() {
        $object = new CDocumentFolder();
        $object->parent_id = CRequest::getInt("parent");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_documents/_folder/add.tpl");
    }
    public function actionEdit() {
        $object = CDocumentsManager::getFolder(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=index&parent=".$object->parent_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_documents/_folder/edit.tpl");
    }
    public function actionDelete() {
        $object = CDocumentsManager::getFolder(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("index.php?action=index");
    }
    public function actionSave() {
        $object = new CDocumentFolder();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("index.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("index.php?action=index&parent=".$object->parent_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_documents/_folder/edit.tpl");
    }
    public function actionConvert() {
        $foldersLookup = array(
            "gost1" => "Должностные инструкции",
            "gost2" => "Образовательные стандарты",
            "gost3" => "Самообследование",
            "gost4" => "Учебные планы",
            "gost5" => "К диплому",
            "gost6" => "Интеллектуальная система самообучения и самоорганизации пользователей веб-портала",
            "gost7" => "СДО MOODLE",
            "gost8" => "Практика",
            "gost9" => "Материалы для оформления УМК"
        );
        // создадим папки
        foreach ($foldersLookup as $key=>$title) {
            $folder = new CDocumentFolder();
            $folder->title = $title;
            $folder->save();
            // сконвертим файлы в папках
            $query = new CQuery();
            $query->select("f.*")
                ->from(TABLE_DOCUMENTS." as f")
                ->condition("nameFolder = '".$key."'");
            foreach ($query->execute()->getItems() as $arr) {
                $ar = new CDocumentActiveRecord($arr);
                $ar->setTable(TABLE_DOCUMENTS);
                $file = new CDocumentFile($ar);
                $file->folder_id = $folder->getId();
                $file->save();
            }
        }
        $this->redirect("?action=index");
    }
}