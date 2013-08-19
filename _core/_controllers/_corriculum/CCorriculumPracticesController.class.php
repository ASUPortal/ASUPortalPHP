<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.03.13
 * Time: 16:01
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumPracticesController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Индивидуальные учебные планы");

        parent::__construct();
    }
    public function actionAdd() {
        $p = new CCorriculumPractice();
        $p->corriculum_id = CRequest::getInt("id");
        $this->setData("practice", $p);
        $this->renderView("_corriculum/_practices/add.tpl");
    }
    public function actionEdit() {
        $p = CCorriculumsManager::getPractice(CRequest::getInt("id"));
        $this->setData("practice", $p);
        $this->renderView("_corriculum/_practices/edit.tpl");
    }
    public function actionDel() {
        $p = CCorriculumsManager::getPractice(CRequest::getInt("id"));
        $c = $p->corriculum_id;
        $p->remove();
        $this->redirect("index.php?action=edit&id=".$c);
    }
    public function actionSave() {
        $p = new CCorriculumPractice();
        $p->setAttributes(CRequest::getArray($p::getClassName()));
        if ($p->validate()) {
            $p->save();
            if ($this->continueEdit()) {
                $this->redirect("practices.php?action=edit&id=".$p->getId());
            } else {
                $this->redirect("index.php?action=edit&id=".$p->corriculum_id);
            }
            return true;
        }
        $this->setData("practice", $p);
        $this->renderView("_corriculum/_practices/edit.tpl");
    }
}
