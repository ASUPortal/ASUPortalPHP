<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.03.13
 * Time: 15:50
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumCyclesController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Индивидуальные учебные планы");

        parent::__construct();
    }
    public function actionEdit() {
        $cycle = CCorriculumsManager::getCycle(CRequest::getInt("id"));
        /**
         * Подключаем скрипты
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        /**
         * Собираем данные для распределения нагрузки по видам
         */
        $labors = new CArrayList();
        foreach ($cycle->disciplines->getItems() as $discipline) {
            foreach ($discipline->labors->getItems() as $labor) {
                $labors->add($labor->type_id, $labor);
            }
        }
        $this->setData("labors", $labors);
        $this->setData("cycle", $cycle);
        $this->renderView("_corriculum/_cycles/edit.tpl");
    }
    public function actionAdd() {
        $cycle = new CCorriculumCycle();
        $cycle->corriculum_id = CRequest::getInt("id");
        $this->setData("cycle", $cycle);
        $this->renderView("_corriculum/_cycles/add.tpl");
    }
    public function actionSave() {
        $cycle = new CCorriculumCycle();
        $cycle->setAttributes(CRequest::getArray($cycle::getClassName()));
        if ($cycle->validate()) {
            $cycle->save();
            if ($this->continueEdit()) {
                $this->redirect("cycles.php?action=edit&id=".$cycle->getId());
            } else {
                $this->redirect("index.php?action=view&id=".$cycle->corriculum->getId());
            }
            return true;
        }
        $this->setData("cycle", $cycle);
        $this->renderView("_corriculum/_cycles/add.tpl");
    }
    public function actionDel() {
        $cycle = CCorriculumsManager::getCycle(CRequest::getInt("id"));
        $id = $cycle->corriculum_id;
        $cycle->remove();
        $this->redirect("index.php?action=view&id=".$id);
    }
}
