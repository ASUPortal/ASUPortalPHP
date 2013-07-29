<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 21.11.12
 * Time: 21:39
 * To change this template use File | Settings | File Templates.
 */
class CPrintFormController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление шаблонами документов");

        parent::__construct();
    }
    public function actionIndex() {
        $set = CActiveRecordProvider::getAllFromTable(TABLE_PRINT_FORMS);
        $forms = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $form = new CPrintForm($item);
            $forms->add($form->getId(), $form);
        }
        $this->setData("forms", $forms);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_print/form/index.tpl");
    }
    public function actionAdd() {
        $form = new CPrintForm();
        $formsets = array();
        foreach (CPrintManager::getAllFormsets()->getItems() as $formset) {
            $formsets[$formset->id] = $formset->title;
        }
        $types = array(
            "docx" => "Microsoft Word (docx)",
            "odt" => "OpenOffice Writer (odt)"
        );
        $this->setData("types", $types);
        $this->setData("form", $form);
        $this->setData("formsets", $formsets);
        $this->renderView("_print/form/edit.tpl");
    }
    public function actionSave() {
        $form = new CPrintForm();
        $form->debug = 0;
        $form->isActive = 0;
        $form->setAttributes(CRequest::getArray(CPrintForm::getClassName()));
        if ($form->validate()) {
            $form->save();
            $this->redirect("?action=index");
        }
        $formsets = array();
        foreach (CPrintManager::getAllFormsets()->getItems() as $formset) {
            $formsets[$formset->id] = $formset->title;
        }
        $this->setData("form", $form);
        $this->setData("formsets", $formsets);
        $this->renderView("_print/form/edit.tpl");
    }
    public function actionEdit() {
        $form = CPrintManager::getForm(CRequest::getInt("id"));
        $formsets = array();
        foreach (CPrintManager::getAllFormsets()->getItems() as $formset) {
            $formsets[$formset->id] = $formset->title;
        }
        $types = array(
            "docx" => "Microsoft Word (docx)",
            "odt" => "OpenOffice Writer (odt)"
        );
        $this->setData("types", $types);
        $this->setData("form", $form);
        $this->setData("formsets", $formsets);
        $this->renderView("_print/form/edit.tpl");
    }
    public function actionDelete() {
        $form = CPrintManager::getForm(CRequest::getInt("id"));
        $form->remove();
        $this->redirect("?action=index");
    }
}
