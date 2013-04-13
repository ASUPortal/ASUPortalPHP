<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 13.04.13
 * Time: 13:02
 * To change this template use File | Settings | File Templates.
 */

class CPublicPagesController extends CBaseController{
    private $allowedAnonymous = array(
        "view"
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
        $this->setPageTitle("Страницы портала кафедры АСУ");

        parent::__construct();
    }
    public function actionView() {
        $page = CPageManager::getPage(CRequest::getInt("id"));
        if (!is_null($page)) {
            $this->setData("page", $page);
            $this->renderView("_pages/public.view.tpl");
        }
    }
}