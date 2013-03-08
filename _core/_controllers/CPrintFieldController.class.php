<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 21.11.12
 * Time: 22:59
 * To change this template use File | Settings | File Templates.
 */
class CPrintFieldController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление приказами");

        parent::__construct();
    }
    public function actionIndex() {
        $set = CActiveRecordProvider::getAllFromTable(TABLE_PRINT_FIELDS);
        $fields = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $field = CPrintManager::getField($item->getId());
            $fields->add($field->getId(), $field);
        }
        $this->setData("fields", $fields);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_print/field/index.tpl");
    }
    public function actionAdd() {
		$field = new CPrintField();
		$formsets = array();
		foreach (CPrintManager::getAllFormsets()->getItems() as $formset) {
			$formsets[$formset->id] = $formset->title;
		}
        $fields = array();
        foreach (CPrintManager::getAllFields()->getItems() as $f) {
            $fields[$f->getId()] = $f->title;
        }
        $types = array(
            1 => "Текстовый описатель",
            2 => "Вывод таблицы"
        );
        $this->setData("types", $types);
        $this->setData("fields", $fields);
		$this->setData("formsets", $formsets);
		$this->setData("field", $field);
		$this->renderView("_print/field/edit.tpl");
    }
    public function actionEdit() {
		$field = CPrintManager::getField(CRequest::getInt("id"));
		$formsets = array();
		foreach (CPrintManager::getAllFormsets()->getItems() as $formset) {
			$formsets[$formset->id] = $formset->title;
		}
        $fields = array();
        foreach (CPrintManager::getAllFields()->getItems() as $f) {
            $fields[$f->getId()] = $f->title;
        }
        $types = array(
            1 => "Текстовый описатель",
            2 => "Вывод таблицы"
        );
        $parents = array(
            "table-row" => "Строка таблицы"
        );
        $this->setData("parents", $parents);
        $this->setData("types", $types);
        $this->setData("fields", $fields);
		$this->setData("formsets", $formsets);
		$this->setData("field", $field);
		$this->renderView("_print/field/edit.tpl");
    }
    public function actionSave() {
        $field = new CPrintField();
        $field->setAttributes(CRequest::getArray($field::getClassName()));
        if ($field->validate()) {
        	$field->save();
        	$this->redirect("?action=index");
        	return true;
        }
        $formsets = array();
        foreach (CPrintManager::getAllFormsets()->getItems() as $formset) {
        	$formsets[$formset->id] = $formset->title;
        }
        $types = array(
            1 => "Текстовый описатель",
            2 => "Вывод таблицы"
        );
        $parents = array(
            "table-row" => "Строка таблицы"
        );
        $this->setData("types", $types);
        $this->setData("formsets", $formsets);
        $this->setData("field", $field);
        $this->renderView("_print/field/edit.tpl");        
    }
}
