<?php
/**
 * Description of CCorriculumsController
 *
 * @author TERRAN
 */
class CCorriculumsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Индивидуальные учебные планы");

        parent::__construct();        
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("corr.*")
            ->from(TABLE_CORRICULUMS." as corr")
            ->order("corr.id desc");
        $set->setQuery($query);
        $corriculums = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $corriculum = new CCorriculum($item);
            $corriculums->add($corriculum->getId(), $corriculum);
        }
        /**
         * Передаем данные
         */
        $this->setData("paginator", $set->getPaginator());
        $this->setData("corriculums", $corriculums);
        $this->renderView("_corriculum/_plan/index.tpl");
    }
    public function actionAdd() {
        $corriculum = new CCorriculum();
        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/add.tpl");
    }
    public function actionEdit() {
        $corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
        /**
         * Подключаем скрипты
         */
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/edit.tpl");
    }
    public function actionSave() {
        $corriculum = new CCorriculum();
        $corriculum->setAttributes(CRequest::getArray($corriculum::getClassName()));
        if ($corriculum->validate()) {
            $corriculum->save();
            $this->redirect("?action=view&id=".$corriculum->getId());
            return true;
        }
        $this->setData("corriculum", $corriculum);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->renderView("_corriculum/_plan/edit.tpl");
    }
    public function actionView() {
        $corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
        /**
         * По дисциплинам получаем все виды занятий.
         * Нужно для того, чтобы сформировать шапку таблицы
         */
        $labors = new CArrayList();
        foreach ($corriculum->cycles->getItems() as $cycle) {
            foreach ($cycle->disciplines->getItems() as $discipline) {
                foreach ($discipline->labors->getItems() as $labor) {
                    $labors->add($labor->type_id, $labor);
                }
            }
        }
        /**
         * Передаем данные представлению
         */
        $this->setData("labors", $labors);
        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/view.tpl");
    }
    public function actionCopy() {
        $corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
        /**
         * Клонируем сам учебный план
         */
        $newCorriculum = $corriculum->copy();
        $newCorriculum->title = "Копия ".$newCorriculum->title;
        $newCorriculum->save();
        /**
         * Клонируем практики учебного плана
         */
        foreach ($corriculum->practices->getItems() as $practice) {
            $newPractice = $practice->copy();
            $newPractice->corriculum_id = $newCorriculum->getId();
            $newPractice->save();
        }
        /**
         * Клонируем циклы учебного плана
         */
        foreach ($corriculum->cycles->getItems() as $cycle) {
            $newCycle = $cycle->copy();
            $newCycle->corriculum_id = $newCorriculum->getId();
            $newCycle->save();
            /**
             * Клонируем дисциплины из циклов
             */
            foreach ($cycle->disciplines->getItems() as $discipline) {
                $newDiscipline = $discipline->copy();
                $newDiscipline->cycle_id = $newCycle->getId();
                $newDiscipline->save();
                /**
                 * Клонируем нагрузку из дисциплин
                 */
                foreach ($discipline->labors->getItems() as $labor) {
                    $newLabor = $labor->copy();
                    $newLabor->discipline_id = $newDiscipline->getId();
                    $newLabor->save();
                }
				// копируем дочерние дисциплины
				foreach ($discipline->children->getItems() as $child) {
					$newChildDiscipline = $child->copy();
					$newChildDiscipline->parent_id = $newDiscipline->getId();
					$newChildDiscipline->cycle_id = $newCycle->getId();
					$newChildDiscipline->save();
					/**
					 * Клонируем нагрузку из дисциплин
					 */
					foreach ($child->labors->getItems() as $labor) {
						$newLabor = $labor->copy();
						$newLabor->discipline_id = $newChildDiscipline->getId();
						$newLabor->save();
					}
				}
            }
        }
        /**
         * Все, редирект на страницу со списком
         */
        $this->redirect("index.php?action=index");
    }
    public function actionDelete() {
        $corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
        /**
         * Удаляем практики из плана
         */
        foreach ($corriculum->practices->getItems() as $practice) {
            $practice->remove();
        }
        /**
         * Удаляем циклы
         */
        foreach ($corriculum->cycles->getItems() as $cycle) {
            /**
             * Удаляем дисциплины из циклов
             */
            foreach ($cycle->disciplines->getItems() as $discipline) {
                /**
                 * Удаляем нагрузку из дисциплин
                 */
                foreach ($discipline->labors->getItems() as $labor) {
                    $labor->remove();
                }
                $discipline->remove();
            }
            $cycle->remove();
        }
        /**
         * Удаляем сам учебный план
         */
        $corriculum->remove();
        /**
         * Все, редирект на страницу со списком
         */
        $this->redirect("index.php?action=index");
    }
    /*





    public function actionSave() {
        if (CRequest::getInt("id") == 0) {
            $corriculum = CFactory::createCorriculum();
        } else {
            $corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
        }
        
        $corriculum->direction = CTaxonomyManager::getSpeciality(CRequest::getInt("direction_id"));
        $corriculum->basic_education = CRequest::getString("basic_education");
        $corriculum->save();
        
        $this->redirect("?action=index");
    }
    public function actionView() {
        $id = CRequest::getInt("id");
        $corriculum = CCorriculumsManager::getCorriculum($id);

        // получаем лист всех видов трудоемкостей, чтобы
        // по нему сформировать таблицу
        $labors = new CArrayList();
        $controls = new CArrayList();
        $periods = new CArrayList();
        foreach ($corriculum->cycles->getItems() as $cycle) {
            foreach ($cycle->disciplines->getItems() as $discipline) {
                foreach ($discipline->labors->getItems() as $labor) {
                    $labors->add($labor->type_id, $labor);
                }
                foreach ($discipline->controls->getItems() as $control) {
                    $controls->add($control->form_id, $control);
                }
                foreach ($discipline->hours->getItems() as $hour) {
                    $periods->add($hour->period, $hour);
                }
            }
        }

        $this->setData("periods", $periods);
        $this->setData("controls", $controls);
        $this->setData("labors", $labors);
        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/view.tpl");
    }
    public function actionAddDiscipline() {
        $cycyle = CCorriculumsManager::getCycle(CRequest::getInt("cycle_id"));
        $type = CRequest::getInt("type");

        $this->setData("cycle", $cycyle);
        $this->setData("type", $type);
        // обязательная часть
        if ($type == 1) {
            $this->renderView("_corriculum/_plan/addDiscipline.basic.tpl");
        // вариативная часть
        } elseif ($type == 2) {
            $this->renderView("_corriculum/_plan/addDiscipline.variant.tpl");
        }
    }
    public function actionAddCycle() {
        $id = CRequest::getInt("corriculum_id");
        $corriculum = CCorriculumsManager::getCorriculum($id);

        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/addCycle.tpl");
    }
    public function actionSaveCycle() {
        if (CRequest::getInt("id") == 0) {
            $cycle = CFactory::createCorriculumCycle();
        } else {
            $cycle = null;
        }

        $cycle->title = CRequest::getString("title");
        $cycle->number = CRequest::getString("number");
        $cycle->corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("corriculum_id"));
        $cycle->save();

        $this->redirect("?action=view&id=".$cycle->corriculum->id);
    }
    public function actionViewCycle() {
        $cycle = CCorriculumsManager::getCycle(CRequest::getInt("id"));

        $this->setData("cycle", $cycle);
        $this->renderView("_corriculum/_plan/view.cycle.tpl");
    }
    public function actionSaveDiscipline() {
        if (CRequest::getInt("id") == 0) {
            $discipline = CFactory::createCorriculumDiscipline();
        } else {
            $discipline = null;
        }

        $discipline->discipline_id = CRequest::getInt("discipline_id");
        $discipline->type = CRequest::getInt("type");
        $discipline->cycle_id = CRequest::getInt("cycle_id");
        $discipline->parent_id = CRequest::getInt("parent_id");
        $discipline->number = CRequest::getString("number");
        $discipline->save();

        $this->redirect("?action=viewCycle&id=".$discipline->cycle->id);
    }
    public function actionViewDiscipline() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));

        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_plan/view.discipline.tpl");
    }
    public function actionAddLabor() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));

        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_plan/addLabor.tpl");
    }
    public function actionSaveLabor() {
        if (CRequest::getInt("id") == 0) {
            $labor = CFactory::createCorriculumDisciplineLabor();
        } else {
            $labor = null;
        }

        $labor->discipline_id = CRequest::getInt("discipline_id");
        $labor->type_id = CRequest::getInt("type_id");
        $labor->form_id = CRequest::getInt("form_id");
        $labor->value = CRequest::getInt("value");
        $labor->save();

        $this->redirect("?action=viewDiscipline&id=".$labor->discipline_id);
    }
    public function actionAddControl() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        $control = CFactory::createCorriculumDisciplineControl();
        $control->discipline = $discipline;

        $this->setData("discipline", $discipline);
        $this->setData("control", $control);
        $this->renderView("_corriculum/_plan/addControl.tpl");
    }
    public function actionSaveControl() {
        if (CRequest::getInt("id", CCorriculumDisciplineControl::getClassName()) != 0) {
            $control = CCorriculumsManager::getControl(CRequest::getInt("id", CCorriculumDisciplineControl::getClassName()));
        } else {
            $control = CFactory::createCorriculumDisciplineControl();
        }
        $control->setAttributes(CRequest::getArray(CCorriculumDisciplineControl::getClassName()));
        if ($control->validate()) {
            $control->save();
            $this->redirect("?action=viewDiscipline&id=".$control->discipline_id);
        }

        $this->setData("discipline", $control->discipline);
        $this->setData("control", $control);
        $this->renderView("_corriculum/_plan/addControl.tpl");
    }
    public function actionAddHour() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        $hour = CFactory::createCorriculumDisciplineHour();
        $hour->discipline = $discipline;

        $this->setData("hour", $hour);
        $this->renderView("_corriculum/_plan/addHour.tpl");
    }
    public function actionSaveHour() {
        if (CRequest::getInt("id", CCorriculumDisciplineHour::getClassName()) != 0) {
            $hour = CCorriculumsManager::getHour(CRequest::getInt("id", CCorriculumDisciplineHour::getClassName()));
        } else {
            $hour = CFactory::createCorriculumDisciplineHour();
        }
        $hour->setAttributes(CRequest::getArray(CCorriculumDisciplineHour::getClassName()));
        if ($hour->validate()) {
            $hour->save();
            $this->redirect("?action=viewDiscipline&id=".$hour->discipline->id);
        }

        $this->setData("hour", $hour);
        $this->renderView("_corriculum/_plan/addHour.tpl");
    }
    */
}

?>
