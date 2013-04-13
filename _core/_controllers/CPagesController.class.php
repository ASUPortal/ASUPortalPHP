<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 13.04.13
 * Time: 17:57
 * To change this template use File | Settings | File Templates.
 */

class CPagesController extends CBaseController{
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
        $this->setPageTitle("Редактирование страниц портала");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("page.*")
            ->from(TABLE_PAGES." as page");
        if (!CSession::getCurrentUser()->hasRole("pages_admin")) {
            $query->condition("page.user_id_insert = ".CSession::getCurrentUser()->getId());
        }
        $pages = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $page = new CPage($ar);
            $pages->add($page->getId(), $page);
        }
        $this->setData("paginator", $set->getPaginator());
        $this->setData("pages", $pages);
        $this->renderView("_pages/index.tpl");
    }
    public function actionAdd() {
        $page = new CPage();
        $page->user_id_insert = CSession::getCurrentUser()->getId();
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("page", $page);
        $this->renderView("_pages/add.tpl");
    }
    public function actionEdit() {
        $page = CPageManager::getPage(CRequest::getInt("id"));
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("page", $page);
        $this->renderView("_pages/edit.tpl");
    }
    public function actionDelete() {
        $page = CPageManager::getPage(CRequest::getInt("id"));
        $page->remove();
        $this->redirect("admin.php?aciton=index");
    }
    public function actionSave() {
        $page = new CPage();
        $page->setAttributes(CRequest::getArray($page::getClassName()));
        if ($page->validate()) {
            $page->save();
            $this->redirect("admin.php?action=index");
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("page", $page);
        $this->renderView("_pages/edit.tpl");
    }
}