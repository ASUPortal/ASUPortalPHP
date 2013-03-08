<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.03.13
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumDisciplinesController extends CBaseController {
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
        /**
         * Подключаем скрипты для няшности
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/edit.tpl");
    }
    public function actionAdd() {
        $discipline = new CCorriculumDiscipline();
        $discipline->cycle_id = CRequest::getInt("id");
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/add.tpl");
    }
    public function actionSave() {
        $discipline = new CCorriculumDiscipline();
        $discipline->setAttributes(CRequest::getArray($discipline::getClassName()));
        if ($discipline->validate()) {
            $discipline->save();
            $this->redirect("cycles.php?action=edit&id=".$discipline->cycle_id);
            return true;
        }
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/add.tpl");
    }
    public function actionDel() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        $id = $discipline->cycle_id;
        $discipline->remove();
        $this->redirect("cycles.php?action=edit&id=".$id);
    }
}
