<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 24.09.12
 * Time: 22:15
 * To change this template use File | Settings | File Templates.
 */
class CRatingPersonsIndexesController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление доступом пользователей");

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
            if ($person->getRatingIndexesByYear($year)->getCount() > 0) {
                $persons->add($person->id, $person);
            }
        }
        $this->setData("year", $year);
        $this->setData("persons", $persons);
        $this->renderView("_rating/person/index.tpl");
    }
    public function actionAdd() {
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/personTypeFilter.js");
        $form = new CPersonRatingIndexesForm();
        $form->indexes = array();
        $year = CUtils::getCurrentYear();
        if (CRequest::getInt("year") != 0) {
            $year = CTaxonomyManager::getYear(CRequest::getInt("year"));
        }
        $form->year_id = $year->getId();
        $this->setData("year", $year);
        $this->setData("form", $form);
        $this->renderView("_rating/person/add.tpl");
    }
    public function actionEdit() {
        $person = CStaffManager::getPerson(CRequest::getInt("id"));
        $year = CTaxonomyManager::getYear(CRequest::getInt("year"));
        if (is_null($person)) {
            $this->redirectNoAccess();
        }
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/personTypeFilter.js");
        $form = new CPersonRatingIndexesForm();
        $form->person_id = $person->getId();
        $indexes = array();
        foreach ($person->getRatingIndexesByYear($year)->getItems() as $index) {
            foreach ($index->getIndexValues()->getItems() as $value) {
                $indexes[$value->getId()] = $value->getId();
            }
        }
        $form->indexes = $indexes;
        $form->year_id = $year->getId();
        $this->setData("form", $form);
        $this->setData("year", $year);
        $this->renderView("_rating/person/edit.tpl");
    }
    public function actionSave() {
        $form = new CPersonRatingIndexesForm();
        $form->setAttributes(CRequest::getArray(CPersonRatingIndexesForm::getClassName()));
        if ($form->validate()) {
            // удаляем старые показатели преподавателя в выбранном году
            foreach(CActiveRecordProvider::getWithCondition(TABLE_PERSON_RATINGS, "person_id = ".$form->person_id." and year_id = ".$form->year_id)->getItems() as $item) {
                $item->remove();
            }
            foreach ($form->getIndexes()->getItems() as $indexValue) {
                $personValue = new CPersonRatingIndex();
                $personValue->person_id = $form->person_id;
                $personValue->year_id = $form->year_id;
                $personValue->index_id = $indexValue->id;
                $personValue->save();
            }
            $this->redirect("persons.php?action=index");
        }
        $this->setData("form", $form);
        $this->renderView("_rating/person/add.tpl");
    }
    public function actionView() {
        $thisPerson = CStaffManager::getPerson(CRequest::getInt("id"));
        if (is_null($thisPerson)) {
            $this->redirectNoAccess();
        }
        $year = CTaxonomyManager::getYear(CRequest::getInt("year"));
        $this->setData("year", $year);
        $this->setData("person", $thisPerson);
        /**
         * С человеком есть связанные показатели. Надо взять глобальные объекты этих
         * показателей и посчитать их значения по всем людям в указанном году.
         *
         * Берем через менеджер, так как у персона свои личные показатели.
         */
        $indexes = new CArrayList();
        foreach ($thisPerson->getRatingIndexesByYear($year)->getItems() as $index) {
            $index = CRatingManager::getRatingIndex($index->getId());
            $indexes->add($index->getId(), $index);
        }
        $this->setData("indexes", $indexes);
        $this->renderView("_rating/person/view.tpl");
    }
    public function actionDelete() {
        $person = CStaffManager::getPerson(CRequest::getInt("id"));
        $year = CTaxonomyManager::getYear(CRequest::getInt("year"));
        if (!is_null($person)) {
            if (!is_null($year)) {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_PERSON_RATINGS, "person_id = ".$person->getId()." and year_id = ".$year->getId())->getItems() as $item) {
                    $item->remove();
                }
            }
        }
        $this->redirect("?action=index");
    }
    public function actionFill() {
        $year = CTaxonomyManager::getYear(CRequest::getInt("year"));
        if (is_null($year)) {
            $year = CUtils::getCurrentYear();
        }
        $form = new CPersonRatingAutofillForm();
        $form->year_id = $year->getId();
        $this->addJSInclude("_core/personTypeFilter.js");
        $this->setData("form", $form);
        $this->renderView("_rating/person/fill.tpl");
    }
    public function actionFillIndexes() {
        $form = new CPersonRatingAutofillForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        $year = CTaxonomyManager::getYear($form->year_id);
        // берем показатели указанного года
        foreach (CRatingManager::getRatingIndexesByYear($year)->getItems() as $index) {
            foreach ($form->persons as $personId) {
                $person = CStaffManager::getPerson($personId);
                if (!is_null($person)) {
                    // удаляем старые показатели
                    foreach(CActiveRecordProvider::getWithCondition(TABLE_PERSON_RATINGS, "person_id = ".$person->getId()." and year_id = ".$year->getId())->getItems() as $item) {
                        $item->remove();
                    }
                    // если поле не многозначное, то способ вычисления лежит в показателе
                    if (!$index->isMultivalue()) {
                        if ($index->person_method != "") {
                            $v = null;
                            eval('$v = $person->'.$index->person_method.';');
                            if (!is_null($v)) {
                                if (strtoupper(get_class($v)) == "CTERM") {
                                    if ($index->getIndexValues()->hasElement($v->getId())) {
                                        $personValue = new CPersonRatingIndex();
                                        $personValue->person_id = $person->getId();
                                        $personValue->year_id = $year->getId();
                                        $personValue->index_id = $index->getIndexValues()->getItem($v->getId())->getId();
                                        $personValue->save();
                                    }
                                }
                            }
                        }
                    } else {
                        /**
                         * Это многозначный показатель, вычисляется отдельно каждое значение
                         */
                        foreach ($index->getIndexValues()->getItems() as $indexValue) {
                            if ($indexValue->evaluate_code != "") {
                                if ($indexValue->evaluate_method == "1") {
                                    // это sql-запрос
                                    $query = $indexValue->evaluate_code;
                                    $query = str_replace("%person%", $person->getId(), $query);
                                    $query = str_replace("%year%", $year->getId(), $query);
                                    $res = mysql_query($query);
                                    if (mysql_errno() == 0) {
                                        while ($row = mysql_fetch_assoc($res)) {
                                            if (array_key_exists("res", $row)) {
                                                if ($row['res'] == "1") {
                                                    $personValue = new CPersonRatingIndex();
                                                    $personValue->person_id = $person->getId();
                                                    $personValue->year_id = $year->getId();
                                                    $personValue->index_id = $indexValue->getId();
                                                    $personValue->save();
                                                }
                                            }
                                            break;
                                        }
                                    }
                                } elseif ($indexValue->evaluate_method == "2") {
                                    // это php код
                                    $v = null;
                                    $code = $indexValue->evaluate_code;
                                    $code = str_replace("%person%", $person->getId(), $code);
                                    $code = str_replace("%year%", $year->getId(), $code);
                                    eval('$v = '.$code.';');
                                    if ($v === true) {
                                        $personValue = new CPersonRatingIndex();
                                        $personValue->person_id = $person->getId();
                                        $personValue->year_id = $year->getId();
                                        $personValue->index_id = $indexValue->getId();
                                        $personValue->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->redirect("persons.php?action=index");
    }
}
