<?php
class CDiplomPreviewCommissionMembersController extends CBaseController{
	protected $_isComponent = true;
	
    public function __construct() {
        if (!CSession::isAuth()) {
        	$action = CRequest::getString("action");
        	if ($action == "") {
        		$action = "index";
        	}
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }
        $this->_smartyEnabled = true;
        $this->setPageTitle("Члены комиссии по предзащите ВКР");
        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_DIPLOM_PREVIEW_MEMBERS." as t")
            ->condition("comm_id=".CRequest::getInt("comm_id"));
        $members = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $member = new CDiplomPreviewCommissionMember($ar);
            $members->add($member->getId(), $member);
        }
        $this->addActionsMenuItem(array(
        	"title" => "Обновить",
        	"link" => "members.php?action=index&comm_id=".CRequest::getInt("comm_id"),
        	"icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
        	"title" => "Добавить",
        	"link" => "members.php?action=add&comm_id=".CRequest::getInt("comm_id"),
        	"icon" => "actions/list-add.png"
        ));
        $this->setData("members", $members);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_diploms/diplom_preview_members/index.tpl");
    }
    public function actionAdd() {
        $member = new CDiplomPreviewCommissionMember();
        $member->comm_id = CRequest::getInt("comm_id");
        $this->setData("member", $member);
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => WEB_ROOT."_modules/_diploms/members.php?action=index&comm_id=".$member->comm_id,
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->renderView("_diploms/diplom_preview_members/add.tpl");
    }
    public function actionEdit() {
        $member = CBaseManager::getDiplomPreviewCommissionMember(CRequest::getInt("id"));
        $this->setData("member", $member);
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => WEB_ROOT."_modules/_diploms/members.php?action=index&comm_id=".$member->comm_id,
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->renderView("_diploms/diplom_preview_members/edit.tpl");
    }
    public function actionDelete() {
        $member = CBaseManager::getDiplomAntiplagiatCheck(CRequest::getInt("id"));
        $commission = $member->diplom;
        $member->remove();
        $this->redirect("members.php?action=edit&id=".$commission->getId());
    }
    public function actionSave() {
        $member = new CDiplomPreviewCommissionMember();
        $member->setAttributes(CRequest::getArray($member::getClassName()));
        if ($member->validate()) {
        	$member->save();
        	if ($this->continueEdit()) {
        		$this->redirect("members.php?action=edit&id=".$member->getId());
        	} else {
        		$this->redirect("members.php?action=index&comm_id=".$member->comm_id);
        	}
        	return true;
        }
        $this->setData("member", $member);
        $this->renderView("_diploms/diplom_preview_members/edit.tpl");
    }
}