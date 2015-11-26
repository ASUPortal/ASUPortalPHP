<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 11:25
 * To change this template use File | Settings | File Templates.
 */

class CPublicNewsController extends CBaseController {
    public $allowedAnonymous = array(
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
    protected function onActionBeforeExecute() {
    
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $set->setPageSize(10);
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
        //проверка доступности виджета вконтакте
        /*$check_url = @get_headers('http://vk.com/js/api/openapi.js');
        $cache_vk_id = "vk_access";
        if (is_null(CApp::getApp()->cache->get($cache_vk_id))) {
        	$vk = strpos($check_url[0],'200');
        	CApp::getApp()->cache->set($cache_vk_id, $vk);
        }
        $vk_access = CApp::getApp()->cache->get($cache_vk_id);
        $this->setData("vk_access", $vk_access);*/
        $this->setData("news", $news);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_news/public.index.tpl");
    }
}