<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 30.04.13
 * Time: 22:23
 * To change this template use File | Settings | File Templates.
 */

class CGrantOutgoesController extends CBaseController {
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
        $this->setPageTitle("Мероприятия");

        parent::__construct();
    }
    public function actionAdd() {
        $outgo = new CGrantOutgo();
        $outgo->grant_id = CRequest::getInt("grant_id");
        $this->setData("outgo", $outgo);
        $this->renderView("_grants/outgo/add.tpl");
    }
    public function actionEdit() {
        $outgo = CGrantManager::getOutgo(CRequest::getInt("id"));
        $this->setData("outgo", $outgo);
        $this->renderView("_grants/outgo/edit.tpl");
    }
    public function actionSave() {
        $outgo = new CGrantOutgo();
        $outgo->setAttributes(CRequest::getArray($outgo::getClassName()));
        if ($outgo->validate()) {
            $outgo->save();
            $this->redirect("index.php?action=edit&id=".$outgo->grant_id);
            return true;
        }
        $this->setData("outgo", $outgo);
        $this->renderView("_grants/outgo/edit.tpl");
    }
    public function actionDelete() {
        $outgo = CGrantManager::getOutgo(CRequest::getInt("id"));
        $id = $outgo->grant_id;
        $outgo->remove();
        $this->redirect("index.php?action=edit&id=".$id);
    }    
}