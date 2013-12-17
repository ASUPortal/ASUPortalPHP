<?php
class CPublicGrantsController extends CBaseController{
    public $allowedAnonymous = array(
        "index",
        "view"
    );
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
        $this->setPageTitle("Хоздоговора и гранты");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_GRANTS." as t")
            ->order("t.id asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CGrant($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("__public/_grants/index.tpl");
    }
    public function actionView() {
        $object = CGrantManager::getGrant(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->renderView("__public/_grants/view.tpl");
    }
    protected function onActionBeforeExecute() {
        
    }
}
