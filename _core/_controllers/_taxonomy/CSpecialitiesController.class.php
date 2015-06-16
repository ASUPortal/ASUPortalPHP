<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 23.02.13
 * Time: 19:40
 * To change this template use File | Settings | File Templates.
 */
class CSpecialitiesController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Специальности");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("spec.*")
            ->from(TABLE_SPECIALITIES." as spec")
            ->order("spec.id desc");
        $set->setQuery($query);
        $selectedSpeciality = null;
        /**
         * Фильтры
         */
        if (!is_null(CRequest::getFilter("speciality"))) {
            $query->condition("spec.id = ".CRequest::getFilter("speciality"));
            $selectedSpeciality = CTaxonomyManager::getSpeciality(CRequest::getFilter("speciality"));
        }
        /**
         * Получаем данные
         */
        $specialities = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $speciality = new CSpeciality($item);
            $specialities->add($speciality->getId(), $speciality);
        }
        /**
         * Подключаем скрипты
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("selectedSpeciality", $selectedSpeciality);
        $this->setData("specialities", $specialities);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_specialities/index.tpl");
    }
    public function actionEdit() {
        $speciality = CTaxonomyManager::getSpeciality(CRequest::getInt("id"));
        $this->setData("speciality", $speciality);
        $this->renderView("_specialities/edit.tpl");
    }
    public function actionAdd() {
        $speciality = new CSpeciality();
        $this->setData("speciality", $speciality);
        $this->renderView("_specialities/add.tpl");
    }
    public function actionSave() {
        $speciality = new CSpeciality();
        $speciality->setAttributes(CRequest::getArray($speciality::getClassName()));
        if ($speciality->validate()) {
            $speciality->save();
            $this->redirect("?action=index");
            return true;
        }
        $this->setData("speciality", $speciality);
        $this->renderView("_specialities/edit.tpl");
    }
    public function actionDelete() {
        $speciality = CTaxonomyManager::getSpeciality(CRequest::getInt("id"));
        $speciality->remove();
        $this->redirect("?action=index");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("term");
        /**
         * Поиск по названию специальности
         */
        $query = new CQuery();
        $query->select("spec.id, spec.name")
            ->from(TABLE_SPECIALITIES." as spec")
            ->condition("LOWER(spec.name) LIKE '%".mb_strtolower($term)."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "type" => "1",
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
            );
        }
        /**
         * Поиск по комментарию в специальности
         */
        $query = new CQuery();
        $query->select("spec.id, spec.name, spec.comment")
            ->from(TABLE_SPECIALITIES." as spec")
            ->condition("LOWER(spec.comment) LIKE '%".mb_strtolower($term)."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "type" => "1",
                "label" => $item["name"]." (".$item["comment"].")",
                "value" => $item["name"]." (".$item["comment"].")",
                "object_id" => $item["id"],
            );
        }
        echo json_encode($res);
    }
}
