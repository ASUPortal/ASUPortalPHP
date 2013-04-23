<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 11:25
 * To change this template use File | Settings | File Templates.
 */

class CPublicNewsController extends CBaseController {
    private $allowedAnonymous = array(
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
        $this->setPageTitle("Новости портала кафедры АСУ");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $set->setPageSize(5);
        $query = new CQuery();
        $query->select("news.*")
            ->from(TABLE_NEWS." as news")
            ->order("news.id desc");
        $set->setQuery($query);
        $news = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $newsItem = new CNewsItem($ar);
            $news->add($newsItem->getId(), $newsItem);
        }
        $this->setData("news", $news);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_news/public.index.tpl");
    }
}