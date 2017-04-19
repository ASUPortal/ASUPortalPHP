<?php
class CSABCommissionMembersController extends CBaseController{
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
        $this->setPageTitle("Члены комиссии по защите ВКР");
        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_SAB_COMMISSION_MEMBERS." as t")
            ->condition("commission_id=".CRequest::getInt("comm_id"));
        $members = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $member = new CSABCommissionMember($ar);
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
        $this->renderView("_state_attestation/members/index.tpl");
    }
    public function actionAdd() {
        $member = new CSABCommissionMember();
        $member->commission_id = CRequest::getInt("comm_id");
        $this->setData("member", $member);
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => WEB_ROOT."_modules/_state_attestation/members.php?action=index&comm_id=".$member->commission_id,
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->renderView("_state_attestation/members/add.tpl");
    }
    public function actionEdit() {
        $member = CBaseManager::getSABCommissionMember(CRequest::getInt("id"));
        $this->setData("member", $member);
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => WEB_ROOT."_modules/_state_attestation/members.php?action=index&comm_id=".$member->commission_id,
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->renderView("_state_attestation/members/edit.tpl");
    }
    public function actionDelete() {
        $member = CBaseManager::getSABCommissionMember(CRequest::getInt("id"));
        $commission = $member->diplom;
        $member->remove();
        $this->redirect("members.php?action=edit&id=".$commission->getId());
    }
    public function actionSave() {
        $member = new CSABCommissionMember();
        $member->setAttributes(CRequest::getArray($member::getClassName()));
        if ($member->validate()) {
        	$member->save();
        	if ($this->continueEdit()) {
        		$this->redirect("members.php?action=edit&id=".$member->getId());
        	} else {
        		$this->redirect("members.php?action=index&comm_id=".$member->commission_id);
        	}
        	return true;
        }
        $this->setData("member", $member);
        $this->renderView("_state_attestation/members/edit.tpl");
    }
}