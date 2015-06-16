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
        $set = new CRecordSet(true);
        $query = new CQuery();
        $query->select("page.*")
            ->from(TABLE_PAGES." as page")
            ->order("page.type_id asc");
        $pages = new CArrayList();
        $set->setQuery($query);
        if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY or
            CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {

            $query->condition("page.user_id_insert = ".CSession::getCurrentUser()->getId());
        }
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
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("query");
        /**
         * Сначала поищем по названию группы
         */
        $query = new CQuery();
        $query->select("distinct(page.id) as id, page.title as name")
            ->from(TABLE_PAGES." as page")
            ->condition("page.title like '%".$term."%'")
            ->limit(0, 5);
        if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY or
            CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {

            $query->condition("page.title like '%".$term."%' AND page.user_id_insert = ".CSession::getCurrentUser()->getId());
        }
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "field" => "id",
                "value" => $item["id"],
                "label" => $item["name"],
                "class" => "CPage"
            );
        }
        echo json_encode($res);
    }
    public function actionDelete() {
        $page = CPageManager::getPage(CRequest::getInt("id"));
        $page->remove();
        $this->redirect("admin.php?action=index");
    }
    public function actionSave() {
        $page = new CPage();
        $page->setAttributes(CRequest::getArray($page::getClassName()));
        if ($page->validate()) {
            $page->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$page->getId());
            } else {
                $this->redirect("admin.php?action=index");
            }
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