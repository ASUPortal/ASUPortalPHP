<?php
class CDiplomPreviewController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }
        $this->_smartyEnabled = true;
        $this->setPageTitle("Предзащита ВКР - студенты");
        parent::__construct();
    }
    public function actionAdd() {
        $preview = new CDiplomPreview();
        $preview->diplom_id = CRequest::getInt("id");
        if (!is_null($preview->diplom)) {
        	$student = $preview->diplom->student;
        	if (!is_null($student)) {
        		$preview->student_id = $student->getId();
        	}
        }
        $preview->date_preview = date("d.m.Y", mktime());
        $this->setData("preview", $preview);
        $this->addActionsMenuItem(array(
        		array(
					"title" => "Назад",
					"link" => WEB_ROOT."_modules/_diploms/index.php?action=edit&id=".$preview->diplom_id,
					"icon" => "actions/edit-undo.png"
        		)
        ));
        $this->renderView("_diploms/diplom_preview/add.tpl");
    }
    public function actionEdit() {
        $preview = CStaffManager::getDiplomPreview(CRequest::getInt("id"));
        // сконвертим дату из MySQL date в нормальную дату
        // $preview->date_preview = date("Y-d-m H:i:s", strtotime($preview->date_preview));
        $this->setData("preview", $preview);
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => WEB_ROOT."_modules/_diploms/index.php?action=edit&id=".$preview->diplom_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->renderView("_diploms/diplom_preview/edit.tpl");
    }
    public function actionDelete() {
        $preview = CStaffManager::getDiplomPreview(CRequest::getInt("id"));
        $diplom = $preview->diplom;
        $preview->remove();
        $this->redirect("index.php?action=edit&id=".$diplom->getId());
    }
    public function actionSave() {
        $preview = new CDiplomPreview();
        $preview->setAttributes(CRequest::getArray($preview::getClassName()));
        if ($preview->validate()) {
            $preview->save();
            if ($this->continueEdit()) {
                $this->redirect("preview.php?action=edit&id=".$preview->getId());
            } else {
                $this->redirect("index.php?action=edit&id=".$preview->diplom_id);
            }
            return true;
        }
        $this->setData("preview", $preview);
        $this->renderView("_diploms/diplom_preview/edit.tpl");
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по ФИО студента
    	*/
    	$query = new CQuery();
    	$query->select("distinct(preview.student_id) as id, student.fio as title");
    	$query->innerJoin(TABLE_STUDENTS." as student", "preview.student_id=student.id")
    	->from(TABLE_DIPLOM_PREVIEWS." as preview")
    	->condition("student.fio like '%".$term."%'")
    	->limit(0, 5);    
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "student_id",
    				"value" => $item["id"],
    				"label" => $item["title"],
    				"class" => "CDiplomPreview"
    		);
    	}
    	echo json_encode($res);
    }
}