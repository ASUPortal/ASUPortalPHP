<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.04.13
 * Time: 16:26
 * To change this template use File | Settings | File Templates.
 */

class CStaffChildrenController extends CBaseController{
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
        $this->setPageTitle("Дети сотрудников");

        parent::__construct();
    }
    public function actionDelete() {
        $child = CStaffManager::getPersonChild(CRequest::getInt("id"));
        $id = $child->kadri_id;
        $child->remove();
        $this->redirect("index.php?action=edit&id=".$id);
    }
    public function actionEdit() {
        $child = CStaffManager::getPersonChild(CRequest::getInt("id"));
        $child->birth_date = date("d.m.Y", strtotime($child->birth_date));
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("child", $child);
        $this->renderView("_staff/child/edit.tpl");
    }
    public function actionAdd() {
        $child = new CPersonChild();
        $child->kadri_id = CRequest::getInt("parent_id");
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("child", $child);
        $this->renderView("_staff/child/add.tpl");
    }
    public function actionSave() {
        $child = new CPersonChild();
        $child->setAttributes(CRequest::getArray($child::getClassName()));
        if ($child->validate()) {
            $child->birth_date = date("Y-m-d", strtotime($child->birth_date));
            $child->save();
            $this->redirect("index.php?action=edit&id=".$child->kadri_id);
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("child", $child);
        $this->renderView("_staff/child/add.tpl");
    }
}