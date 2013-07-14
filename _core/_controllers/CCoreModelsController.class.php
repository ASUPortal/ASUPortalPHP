<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 13:59
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            //$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление моделями данных");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("m.*")
            ->from(TABLE_CORE_MODELS." as m");
        $models = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $model = new CCoreModel($ar);
            $models->add($model->getId(), $model);
        }
        $this->setData("models", $models);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_core/model/index.tpl");
    }
    public function actionAdd() {
        $model = new CCoreModel();
        $this->setData("model", $model);
        $this->renderView("_core/model/add.tpl");
    }
    public function actionEdit() {
        $model = CCoreObjectsManager::getCoreModel(CRequest::getInt("id"));
        $this->setData("model", $model);
        $this->renderView("_core/model/edit.tpl");
    }
    public function actionSave() {
        $model = new CCoreModel();
        $model->setAttributes(CRequest::getArray($model::getClassName()));
        if ($model->validate()) {
            $model->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$model->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $this->setData("model", $model);
        $this->renderView("_core/model/add.tpl");
    }
    public function actionDel() {
        $model = CCoreObjectsManager::getCoreModel(CRequest::getInt("id"));
        $model->remove();
        $this->redirect("?action=index");
    }
}