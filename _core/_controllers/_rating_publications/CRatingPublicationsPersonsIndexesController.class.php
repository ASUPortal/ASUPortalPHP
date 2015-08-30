<?php

class CRatingPublicationsPersonsIndexesController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Показатели преподавателей");

        parent::__construct();
    }
    public function actionIndex() {
		$persons = new CArrayList();
        if (CRequest::getInt("year") == 0) {
            $year = CUtils::getCurrentYear();
        } else {
            $year = CTaxonomyManager::getYear(CRequest::getInt("year"));
        }
        foreach (CStaffManager::getAllPersons()->getItems() as $person) {
            if ($person->getPublications($year)->getCount() > 0) {
                $persons->add($person->id, $person);
            }
        }
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "index.php",
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->setData("year", $year);
        $this->setData("persons", $persons);
        $this->renderView("_rating_publications/person/index.tpl");
    }
}
