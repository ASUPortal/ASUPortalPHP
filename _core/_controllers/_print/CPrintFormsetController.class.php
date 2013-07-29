<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 21.11.12
 * Time: 20:35
 * To change this template use File | Settings | File Templates.
 */
class CPrintFormsetController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление наборами шаблонов");

        parent::__construct();
    }
    public function actionIndex() {
        $sets = new CRecordSet();
        $sets = CActiveRecordProvider::getAllFromTable(TABLE_PRINT_FORMSETS);
        $forms = new CArrayList();
        foreach ($sets->getPaginated()->getItems() as $item) {
            $form = new CPrintFormset($item);
            $forms->add($form->getId(), $form);
        }
        $this->setData("sets", $sets);
        $this->setData("forms", $forms);
        $this->renderView("_print/formset/index.tpl");
    }
    public function actionAdd() {
        $formset = new CPrintFormset();
        $this->setData("set", $formset);
        $this->renderView("_print/formset/edit.tpl");
    }
    public function actionEdit() {
        $formset = CPrintManager::getFormset(CRequest::getInt("id"));
        $this->setData("set", $formset);
        $this->renderView("_print/formset/edit.tpl");
    }
    public function actionSave() {
        $formset = new CPrintFormset();
        $formset->setAttributes(CRequest::getArray($formset::getClassName()));
        if ($formset->validate()) {
            $formset->save();
            $this->redirect("?action=index");
        }
        $this->setData("set", $formset);
        $this->renderView("_print/formset/edit.tpl");
    }
    public function actionExport() {
    	$res = array();
    	foreach (CPrintManager::getAllFormsets()->getItems() as $formset) {
    		$f = array(
				"title" => $formset->title,
    			"alias"	=> $formset->alias,
    			"description" => $formset->description,
    			"context_evaluate" => $formset->context_evaluate,
    			"context_variables" => $formset->context_variables
    		);
    		$res[] = $f;
    	}
    	$this->setData("data", serialize($res));
    	$this->renderView("_print/formset/export.tpl");
    }
    public function actionImport() {
    	
    }
    public function actionDelete() {
        $formset = CPrintManager::getFormset(CRequest::getInt("id"));
        $formset->remove();
        $this->redirect("?action=index");
    }
}
