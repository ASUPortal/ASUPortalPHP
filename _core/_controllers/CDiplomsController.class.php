<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 24.02.13
 * Time: 17:19
 * To change this template use File | Settings | File Templates.
 */
class CDiplomsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Дипломные темы студентов");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("diplom.*")
            ->from(TABLE_DIPLOMS." as diplom")
            ->order("diplom.id desc");
        $set->setQuery($query);
        $diploms = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $diplom = new CDiplom($item);
            $diploms->add($diplom->getId(), $diplom);
        }
        $this->setData("diploms", $diploms);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_diploms/index.tpl");
    }
    public function actionAdd() {
        $diplom = new CDiplom();
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->setData("diplom", $diplom);
        $this->renderView("_diploms/add.tpl");
    }
    public function actionEdit() {
        $diplom = CStaffManager::getDiplom(CRequest::getInt("id"));
        // сконвертим дату из MySQL date в нормальную дату
        $diplom->date_act = date("d.m.Y", strtotime($diplom->date_act));
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->setData("diplom", $diplom);
        $this->renderView("_diploms/edit.tpl");
    }
    public function actionSave() {
        $diplom = new CDiplom();
        $diplom->setAttributes(CRequest::getArray($diplom::getClassName()));
        if ($diplom->validate()) {
            // дату нужно сконвертить в MySQL date
            $diplom->date_act = date("Y-m-d", strtotime($diplom->date_act));
            $diplom->save();
            $this->redirect("?action=index");
            return true;
        }
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        // сконвертим дату из MySQL date в нормальную дату
        $diplom->date_act = date("d.m.Y", strtotime($diplom->date_act));
        $this->setData("diplom", $diplom);
        $this->renderView("_diploms/edit.tpl");
    }
}
