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
        $this->setPageTitle("Управление полями печати");

        parent::__construct();
    }
    public function actionIndex() {
        $set = CActiveRecordProvider::getAllFromTable(TABLE_PRINT_FIELDS);
        $fields = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $field = CPrintManager::getField($item->getId());
            $fields->add($field->getId(), $field);
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
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
    public function actionExport() {
        /**
         * Выбираем данные. Если ничего не получено, то
         * экспортируем все описатели
         */
        $selected = CRequest::getArray("selected");
        $query = new CQuery();
        $query->select("id")
            ->from(TABLE_PRINT_FIELDS);
        if (count($selected) > 0) {
            $query->condition("id in (".implode(", ", $selected).")");
        }
        $fields = array();
        foreach ($query->execute()->getItems() as $item) {
            $field = CPrintManager::getField($item["id"]);
            $field_arr = array(
                "alias" => $field->alias,
                "title" => $field->title,
                "description" => $field->description,
                "value_evaluate" => $field->value_evaluate,
                "parent_node" => $field->parent_node,
            );
            /**
             * Данные о родителе и связанных вставляем как алиасы
             */
            if (!is_null($field->parent)) {
                $field_arr["parent"] = $field->parent->alias;
            }
            /**
             * Данные о наборе форм так же
             */
            if (!is_null($field->formset)) {
                $field_arr["formset"] = $field->formset->alias;
            }
            $fields[] = $field_arr;
        }
        echo serialize($fields);
    }
    public function actionImport() {
        $import_data = CRequest::getString("export_data");
		$import_data = stripsplashes($import_data);
        $import_data = unserialize($import_data);
        /**
         * Первый проход
         * Добавляем или обновляем описатели без учета иерархии
         */
        foreach ($import_data as $field_arr) {
            $field = CPrintManager::getField($field_arr["alias"]);
            if (is_null($field)) {
                $field = new CPrintField();
                $field->alias = $field_arr["alias"];
            }
            /**
             * Обновляем данными из массива
             */
            $field->description = $field_arr["description"];
            $field->value_evaluate = $field_arr["value_evaluate"];
            $field->parent_node = $field_arr["parent_node"];
            $field->title = $field_arr["title"];
            /**
             * Привязываем к набору форм
             */
            $formset = CPrintManager::getFormset($field_arr["formset"]);
            if (is_null($formset)) {
                trigger_error("Не могу найти набор форм ".$field_arr["formset"].". Продолжение невозможно", E_USER_ERROR);
            }
            $field->formset_id = $formset->getId();
            $field->save();
        }
        /**
         * Второй проход
         * Выстраиваем иерархию
         */
        foreach ($import_data as $field_arr) {
            if (array_key_exists("parent", $field_arr)) {
                $parent = CPrintManager::getField($field_arr["parent"]);
                if (is_null($parent)) {
                    trigger_error("Не могу найти описатель с псевдонимом ".$field_arr["parent"].". Импортируйте сначала его", E_USER_ERROR);
                }
                $field = CPrintManager::getField($field_arr["alias"]);
                $field->parent_id = $parent->getId();
                $field->save();
            }
        }
        /**
         * Возвращаемся обратно
         */
        $this->redirect("?action=index");
    }
    public function actionDelete() {
        $field = CPrintManager::getField(CRequest::getInt("id"));
        $field->remove();
        $this->redirect("?action=index");
    }
}
