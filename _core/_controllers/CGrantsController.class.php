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
        $this->renderView("_grants/grant/index.tpl");
    }
    public function actionAdd() {
        $form = new CGrantForm();
        $grant = new CGrant();
        $form->grant = $grant;
        $grant->author_id = CSession::getCurrentPerson()->getId();
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addJSInclude("_core/jquery.form.js");
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("form", $form);
        $this->renderView("_grants/grant/add.tpl");
    }
    public function actionEdit() {
        $grant = CGrantManager::getGrant(CRequest::getInt("id"));
        $form = new CGrantForm();
        $form->grant = $grant;
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addJSInclude("_core/jquery.form.js");
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("form", $form);
        $this->renderView("_grants/grant/edit.tpl");
    }
    public function actionDelete() {
        $grant = CGrantManager::getGrant(CRequest::getInt("id"));
        $grant->remove();
        $this->redirect("admin.php?action=index");
    }
    public function actionSave() {
        $form = new CGrantForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        if ($form->validate()) {
            $form->save();
            $this->redirect("admin.php?action=index");
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $this->setData("form", $form);
        $this->renderView("_grants/grant/edit.tpl");
    }
    public function actionGetUploadForm() {
        $grant = CGrantManager::getGrant(CRequest::getInt("id"));
        $this->setData("grant", $grant);
        $this->renderView("_grants/grant/subform.fileupload.tpl");
    }
    public function actionFileUpload() {
        $grant = new CGrant();
        $grant->setAttributes(CRequest::getArray($grant::getClassName()));
        /**
         * На самом деле с самим грантом мы делать ничего
         * не будем, а создадим вложение
         */
        $attach = new CGrantAttachment();
        $attach->grant_id = $grant->getId();
        $attach->filename = $grant->upload;
        $attach->author_id = CSession::getCurrentPerson()->getId();
        $attach->attach_name = $grant->upload_filename;
        $attach->save();
        /**
         * Если загружать сразу несколько файлов, то
         * иногда возникают какие-то косяки
         */

    }
    public function actionGetAttachmentsSubform() {
        $form = new CGrantForm();
        $form->grant = CGrantManager::getGrant(CRequest::getInt("id"));
        $this->setData("form", $form);
        $this->renderView("_grants/grant/subform.attachments.tpl");
    }
    public function actionSearch() {

    }
}