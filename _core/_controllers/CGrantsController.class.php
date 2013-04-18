<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 01.04.13
 * Time: 9:48
 * To change this template use File | Settings | File Templates.
 */

class CGrantsController extends CBaseController{
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
        $this->setPageTitle("Гранты и все такое");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("gr.*")
            ->from(TABLE_GRANTS." as gr")
            ->order("gr.id desc");
        /**
         * Если пользователь не админ грантов, то
         * видит только свои и те, в которых
         * он участник
         */
        if (!CSession::getCurrentUser()->hasRole(ROLE_GRANTS_ADMIN)) {
            $query->leftJoin(TABLE_GRANT_MEMBERS." as m", "m.grant_id = gr.id");
            $query->condition("gr.author_id=".CSession::getCurrentPerson()->getId()." OR m.person_id=".CSession::getCurrentPerson()->getId());
        }
        $set->setQuery($query);
        $grants = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $grant = new CGrant($ar);
            $grants->add($grant->getId(), $grant);
        }
        $this->setData("grants", $grants);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_grants/index.tpl");
    }
    public function actionAdd() {
        $form = new CGrantForm();
        $grant = new CGrant();
        $form->grant = $grant;
        $grant->author_id = CSession::getCurrentPerson()->getId();
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addJSInclude("_core/jquery.form.js");
        $this->setData("form", $form);
        $this->renderView("_grants/add.tpl");
    }
    public function actionEdit() {
        $grant = CGrantManager::getGrant(CRequest::getInt("id"));
        $form = new CGrantForm();
        $form->grant = $grant;
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addJSInclude("_core/jquery.form.js");
        $this->setData("form", $form);
        $this->renderView("_grants/edit.tpl");
    }
    public function actionDelete() {
        $grant = CGrantManager::getGrant(CRequest::getInt("id"));
        $grant->remove();
        $this->redirect("?action=index");
    }
    public function actionSave() {
        $form = new CGrantForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        if ($form->validate()) {
            $form->save();
            $this->redirect("?action=index");
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("form", $form);
        $this->renderView("_grants/edit.tpl");
    }
    public function actionGetUploadForm() {
        $grant = CGrantManager::getGrant(CRequest::getInt("id"));
        $this->setData("grant", $grant);
        $this->renderView("_grants/subform.fileupload.tpl");
    }
    public function actionFileUpload() {
        $grant = new CGrant();
        $grant->setAttributes(CRequest::getArray($grant::getClassName()));
        var_dump($grant);
    }
    public function actionSearch() {

    }
}