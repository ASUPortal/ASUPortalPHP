<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 15:41
 * To change this template use File | Settings | File Templates.
 */

class CNewsController extends CBaseController {
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
        $this->setPageTitle("Новости портала кафедры АСУ");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("news.*")
            ->from(TABLE_NEWS." as news")
            ->order("news.id desc");
        if (CSession::getCurrentUser()->getStatus() !== USER_TYPE_ADMIN) {
            $query->condition("news.user_id_insert = ".CSession::getCurrentUser()->getId());
        }
        $set->setQuery($query);
        $news = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $newsItem = new CNewsItem($ar);
            $news->add($newsItem->getId(), $newsItem);
        }
        $this->setData("paginator", $set->getPaginator());
        $this->setData("news", $news);
        $this->renderView("_news/index.tpl");
    }
    public function actionAdd() {
        $newsItem = new CNewsItem();
        $newsItem->user_id_insert = CSession::getCurrentUser()->getId();
        $newsItem->date_time = date("d.m.Y");
        $newsItem->news_type = "notice";
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("newsItem", $newsItem);
        $this->renderView("_news/add.tpl");
    }
    public function actionEdit() {
        $newsItem = CNewsManager::getNewsItem(CRequest::getInt("id"));
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("newsItem", $newsItem);
        $this->renderView("_news/edit.tpl");
    }
    public function actionSave() {
        $newsItem = new CNewsItem();
        $newsItem->setAttributes(CRequest::getArray($newsItem::getClassName()));
        if ($newsItem->validate()) {
            $newsItem->date_time = date("Y-m-d H:i:s", strtotime($newsItem->date_time));
            $newsItem->save();
            $this->redirect("?action=index");
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("newsItem", $newsItem);
        $this->renderView("_news/add.tpl");
    }
    public function actionDelete() {
        $newsItem = CNewsManager::getNewsItem(CRequest::getInt("id"));
        $newsItem->remove();
        $this->redirect("?action=index");
    }
}