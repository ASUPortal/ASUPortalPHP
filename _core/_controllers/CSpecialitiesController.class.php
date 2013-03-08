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
        $specialities = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $speciality = new CSpeciality($item);
            $specialities->add($speciality->getId(), $speciality);
        }
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
}
