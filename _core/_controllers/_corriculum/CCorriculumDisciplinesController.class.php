<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.03.13
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumDisciplinesController extends CFlowController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Индивидуальные учебные планы");

        parent::__construct();
    }
    public function actionEdit() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        $disciplineTaxonomy = CDisciplinesManager::getDiscipline($discipline->discipline->getId());
        $corriculum = CCorriculumsManager::getCorriculum($discipline->cycle->corriculum->getId());
        /**
         * Подключаем скрипты для няшности
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        // ссылка для загрузки изданий из библиотеки
        if ($corriculum->link_library != "") {
        	$link = $corriculum->link_library;
        } else {
        	$link = CSettingsManager::getSettingValue("link_library");
        }
        $this->setData("link", $link);
        $this->setData("disciplineTaxonomy", $disciplineTaxonomy);
        $this->setData("cycle", $discipline->cycle);
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/edit.tpl");
    }
    public function actionAdd() {
        $discipline = new CCorriculumDiscipline();
        $discipline->cycle_id = CRequest::getInt("id");
        $this->setData("cycle", CCorriculumsManager::getCycle(CRequest::getInt("id")));
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/add.tpl");
    }
    public function actionSave() {
        $discipline = new CCorriculumDiscipline();
        $discipline->setAttributes(CRequest::getArray($discipline::getClassName()));
        if ($discipline->validate()) {
            $discipline->save();
            if ($this->continueEdit()) {
                $this->redirect("disciplines.php?action=edit&id=".$discipline->getId());
            } else {
                $this->redirect("cycles.php?action=edit&id=".$discipline->cycle_id);
            }
            return true;
        }
        $this->setData("cycle", $discipline->cycle);
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/add.tpl");
    }
    public function actionAddStatement() {
        $statement = new CCorriculumDisciplineStatement();
        $statement->discipline_id = CRequest::getInt("discipline_id");
        $types = array(
            "1" => "основной",
            "2" => "дополнительной"
        );
        $this->setData("types", $types);
        $this->setData("statement", $statement);
        $this->renderView("_corriculum/_disciplines/statementOnBooks/add.tpl");
    }
    public function actionEditStatement() {
        $statement = CBaseManager::getCorriculumDisciplineStatement(CRequest::getInt("id"));
        $types = array(
            "1" => "основной",
            "2" => "дополнительной"
        );
        $this->setData("types", $types);
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Печать по шаблону",
        		"link" => "#",
        		"icon" => "devices/printer.png",
        		"template" => "formset_literature_statements"
        	),
        	array(
        		"title" => "Удалить заявку",
        		"link" => "disciplines.php?action=deleteStatement&id=".$statement->getId(),
        		"icon" => "actions/edit-delete.png"
        	)
        ));
        $this->setData("statement", $statement);
        $this->renderView("_corriculum/_disciplines/statementOnBooks/edit.tpl");
    }
    public function actionSaveStatement() {
        $statement = new CCorriculumDisciplineStatement();
        $statement->setAttributes(CRequest::getArray($statement::getClassName()));
        if ($statement->validate()) {
            $statement->save();
            $discipline = CCorriculumsManager::getDiscipline($statement->discipline_id);
            $this->redirect("disciplines.php?action=editStatement&discipline_id=".$discipline->getId()."&id=".$statement->getId());
            return true;
        }
        $this->renderView("_corriculum/_disciplines/statementOnBooks/edit.tpl");
    }
    public function actionDeleteStatement() {
        $statement = CBaseManager::getCorriculumDisciplineStatement(CRequest::getInt("id"));
        $disciplineId = CCorriculumsManager::getDiscipline($statement->discipline_id)->getId();
        $statement->remove();
        $this->redirect("disciplines.php?action=edit&id=".$disciplineId);
    }
    public function actionDel() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        $id = $discipline->cycle_id;
        /**
         * Удаляем рабочие программы из дисциплины
         */
        foreach ($discipline->plans->getItems() as $plan) {
        	$plan->remove();
        }
        /**
         * Удаляем дочерние дисциплины из родительской
         */
        foreach ($discipline->children->getItems() as $child) {
        	$child->remove();
        }
        $discipline->remove();
        $this->redirect("cycles.php?action=edit&id=".$id);
    }
    public function actionUp() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        /**
         * Проверим, вдруг это первый запуск и ничего не отсортировано еще
         */
        if (is_null($discipline->ordering)) {
            $cycle = $discipline->cycle;
            if (!is_null($cycle)) {
                $i = 1;
                foreach ($cycle->disciplines->getItems() as $d) {
                    $d->ordering = $i;
                    $d->save();
                    $i++;
                }
            }
        }
        /**
         * Двигаем только если текущая не первая
         */
        if ($discipline->ordering > 1) {
            $cycle = $discipline->cycle;
            if (!is_null($cycle)) {
                $d = $cycle->getNthDiscipline(($discipline->ordering - 1));
                if (!is_null($d)) {
                    $curr = $discipline->ordering;
                    $d->ordering = $curr;
                    $discipline->ordering = ($curr - 1);
                    $discipline->save();
                    $d->save();
                }
            }
        }
        /**
         * Возвращаем обратно
         */
        $this->redirect("cycles.php?action=edit&id=".$discipline->cycle_id);
    }
    public function actionDown() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        /**
         * Проверим, вдруг это первый запуск и ничего не отсортировано еще
         */
        if (is_null($discipline->ordering)) {
            $cycle = $discipline->cycle;
            if (!is_null($cycle)) {
                $i = 1;
                foreach ($cycle->disciplines->getItems() as $d) {
                    $d->ordering = $i;
                    $d->save();
                    $i++;
                }
            }
        }
        /**
         * Двигаем только если текущая не последняя
         */
        $cycle = $discipline->cycle;
        if (!is_null($cycle)) {
            if ($discipline->ordering < $cycle->disciplines->getCount()) {
                $curr = $discipline->ordering;
                $d = $cycle->getNthDiscipline($curr + 1);
                if (!is_null($d)) {
                    $d->ordering = $curr;
                    $discipline->ordering = ($curr + 1);
                    $discipline->save();
                    $d->save();
                }
            }
        }
        /**
         * Возвращаем обратно
         */
        $this->redirect("cycles.php?action=edit&id=".$discipline->cycle_id);
    }
    /**
     * Добавление литературы с сайта библиотеки
     */
    public function actionAddFromUrl() {
    	$discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
    	
    	// код дисциплины из библиотеки
    	$codeDiscipl = $discipline->discipline->library_code;
    	// id дисциплины из справочника
    	$subject_id = $discipline->discipline->getId();

    	// ссылка для загрузки изданий из библиотеки
    	$corriculum = CCorriculumsManager::getCorriculum($discipline->cycle->corriculum->getId());
    	if ($corriculum->link_library != "") {
    		$link = $corriculum->link_library;
    	} else {
    		$link = CSettingsManager::getSettingValue("link_library");
    	}
    	
    	$this->setData("message", CDisciplinesManager::addBooksFromUrl($codeDiscipl, $subject_id, false, $link));
    	$this->renderView("_corriculum/_disciplines/addBooks.tpl");
    	//$this->renderView("_flow/dialog.ok.tpl", "", "");
    }
}
