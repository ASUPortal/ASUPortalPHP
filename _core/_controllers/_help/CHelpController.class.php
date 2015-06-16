<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 06.10.12
 * Time: 18:15
 * To change this template use File | Settings | File Templates.
 */
class CHelpController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Справочная система Портала");

        parent::__construct();
    }
    public function actionIndex() {
        $set = CActiveRecordProvider::getAllFromTable(TABLE_HELP);
        $items = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $help = new CHelp($item);
            $items->add($help->getId(), $help);
        }
        $this->setData("helps", $items);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_help/index.tpl");
    }
    public function actionAdd() {
        $help = new CHelp();
        if (CRequest::getString("page") != "") {
            $help->url = CRequest::getString("page");
        }
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("help", $help);
        $this->renderView("_help/add.tpl");
    }
    public function actionSave() {
        $help = new CHelp();
        $help->setAttributes(CRequest::getArray($help::getClassName()));
        if ($help->validate()) {
            $help->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$help->getId());
            } else {
                $this->redirect("?action=index");
            }
        }
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("help", $help);
        $this->renderView("_help/add.tpl");
    }
    public function actionEdit() {
        $help = CHelpManager::getHelp(CRequest::getInt("id"));
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("help", $help);
        $this->renderView("_help/edit.tpl");
    }
    public function actionDelete() {
        $help = CHelpManager::getHelp(CRequest::getInt("id"));
        if (!is_null($help)) {
            $help->remove();
        }
        $this->redirect("?action=index");
    }
    public function actionAutosave() {
        if (CRequest::getInt("id") !== 0) {
            $help = CHelpManager::getHelp(CRequest::getInt("id"));
            if (!is_null($help)) {
                $help->content = CRequest::getString("content", $help::getClassName());
                $help->save();
            }
        }
    }
}
