<?php
class CCorriculumDisciplineBooksController extends CBaseController{
    protected $_isComponent = true;
	
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
    public function actionIndex() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        $this->addActionsMenuItem(array(
            "title" => "Добавить учебник вручную",
            "link" => "books.php?action=add&discipline_id=".$discipline->getId(),
            "icon" => "actions/list-add.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Удалить выделенные учебники",
            "icon" => "actions/edit-delete.png",
            "form" => "#Books",
            "link" => "books.php",
            "action" => "delete"
        ));
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_books/index.tpl");
    }
    public function actionAdd() {
        $object = new CCorriculumBook();
        $this->setData("object", $object);
        
        // для передачи необходимых параметров
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        $param = new CCorriculumDisciplineBook();
        $param->subject_id = $discipline->discipline->getId();
        $param->book_id = $discipline->getId();
        $this->setData("param", $param);
        $this->setData("plan_id", CRequest::getInt("plan_id"));
        $this->setData("type", CRequest::getInt("type"));
        /**
         * Генерация меню
         */
        if (CRequest::getInt("type") == CWorkPlanLiteratureType::WORKPLAN_BASE_LITERATURE or CRequest::getInt("type") == CWorkPlanLiteratureType::WORKPLAN_ADDITIONAL_LITERATURE) {
            $this->addActionsMenuItem(array(
                "title" => "Назад",
                "link" => "workplanliterature.php?action=index&plan_id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
                "icon" => "actions/edit-undo.png"
            ));
            /**
             * Отображение представления
             */
            $this->renderView("_corriculum/_books/addFromComponent.tpl");
        } else {
            $this->addActionsMenuItem(array(
                "title" => "Назад",
                "link" => "books.php?action=index&discipline_id=".$discipline->getId(),
                "icon" => "actions/edit-undo.png"
            ));
            /**
             * Отображение представления
             */
            $this->renderView("_corriculum/_books/add.tpl");
        }
    }
    public function actionEdit() {
        $object = CBaseManager::getCorriculumBook(CRequest::getInt("id"));
        $this->setData("object", $object);
        
        // для передачи необходимых параметров
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        $param = new CCorriculumDisciplineBook();
        $param->subject_id = $discipline->discipline->getId();
        $param->book_id = $discipline->getId();
        $this->setData("param", $param);
        $this->setData("plan_id", CRequest::getInt("plan_id"));
        $this->setData("type", CRequest::getInt("type"));
        /**
         * Генерация меню
         */
        if (CRequest::getInt("type") == CWorkPlanLiteratureType::WORKPLAN_BASE_LITERATURE or CRequest::getInt("type") == CWorkPlanLiteratureType::WORKPLAN_ADDITIONAL_LITERATURE) {
            $this->addActionsMenuItem(array(
                "title" => "Назад",
                "link" => "workplanliterature.php?action=index&plan_id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
                "icon" => "actions/edit-undo.png"
            ));
            /**
             * Отображение представления
             */
            $this->renderView("_corriculum/_books/editFromComponent.tpl");
        } else {
            $this->addActionsMenuItem(array(
                "title" => "Назад",
                "link" => "books.php?action=index&discipline_id=".$discipline->getId(),
                "icon" => "actions/edit-undo.png"
            ));
            /**
             * Отображение представления
             */
            $this->renderView("_corriculum/_books/edit.tpl");
        }
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
        $subject_id = $param->subject_id;
        $discipline_id = $param->book_id;
        $plan_id = CRequest::getInt("plan_id");
        $type = CRequest::getInt("type");
        if ($object->validate()) {
            $object->save();
            $disciplineBook = new CCorriculumDisciplineBook();
            $disciplineBook->book_id = $object->getId();
            $disciplineBook->subject_id = $subject_id;
            $disciplineBook->save();
            if ($this->continueEdit()) {
            	$this->redirect("books.php?action=edit&id=".$object->getId()."&discipline_id=".$discipline_id."&plan_id=".$plan_id."&type=".$type);
            } else {
            	if ($type != 0) {
            		$this->redirect("workplanliterature.php?action=index&plan_id=".$plan_id."&type=".$type);
            	} else {
            		$this->redirect("books.php?action=index&discipline_id=".$discipline_id);
            	}
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_books/edit.tpl");
    }
}