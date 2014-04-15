<?php
class CDocumentFilesController extends CBaseController{
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
        $this->setPageTitle("Управление файлами");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CDocumentFile();
        $object->folder_id = CRequest::getInt("parent");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=index&parent=".$object->folder_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_documents/_file/add.tpl");
    }
    public function actionEdit() {
        $object = CDocumentsManager::getFile(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=index&parent=".$object->folder_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_documents/_file/edit.tpl");
    }
    public function actionDelete() {
        $object = CDocumentsManager::getFile(CRequest::getInt("id"));
        $parent = $object->folder_id;
        $object->remove();
        $this->redirect("index.php?action=index&parent=".$parent);
    }
    public function actionSave() {
        $object = new CDocumentFile();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        $object->setPk("id_file");
        if ($object->validate()) {
            $object->save();
            $this->redirect("index.php?action=index&parent=".$object->folder_id);
            return true;
        }
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=index&parent=".$object->folder_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->setData("object", $object);
        $this->renderView("_documents/_file/edit.tpl");
    }
}