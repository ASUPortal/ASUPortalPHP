<?php
class CCorriculumDisciplineBooksController extends CBaseController{
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
        $this->setPageTitle("Управление учебниками дисциплины");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CCorriculumBook();
        $this->setData("object", $object);
        
        // для передачи необходимых параметров
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        $param = new CCorriculumDisciplineBook();
        $param->discipline_id = $discipline->codeFromLibrary;
        $param->book_id = $discipline->getId();
        $this->setData("param", $param);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "disciplines.php?action=edit&id=".$discipline->getId(),
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_books/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getCorriculumBook(CRequest::getInt("id"));
        $this->setData("object", $object);
        
        // для передачи необходимых параметров
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        $param = new CCorriculumDisciplineBook();
        $param->discipline_id = $discipline->codeFromLibrary;
        $param->book_id = $discipline->getId();
        $this->setData("param", $param);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "disciplines.php?action=edit&id=".$discipline->getId(),
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_books/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getCorriculumBook(CRequest::getInt("id"));
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        if (!is_null($object)) {
        	$object->remove();
        }
        $items = CRequest::getArray("selectedInView");
        foreach ($items as $id){
        	$object = CBaseManager::getCorriculumBook($id);
        	$object->remove();
        }
        $this->redirect("disciplines.php?action=edit&id=".$discipline->getId());
    }
    public function actionSave() {
        $object = new CCorriculumBook();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        $param = new CCorriculumDisciplineBook();
        $param->setAttributes(CRequest::getArray($param::getClassName()));
        $codeDiscipl = $param->discipline_id;
        $discipline_id = $param->book_id;
        if ($object->validate()) {
            $object->save();
            $disciplineBook = new CCorriculumDisciplineBook();
            $disciplineBook->book_id = $object->getId();
            $disciplineBook->discipline_id = $codeDiscipl;
            $disciplineBook->save();
            if ($this->continueEdit()) {
                $this->redirect("books.php?action=edit&id=".$object->getId()."&discipline_id=".$discipline_id);
            } else {
                $this->redirect("disciplines.php?action=edit&id=".$discipline_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_books/edit.tpl");
    }
}