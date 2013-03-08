<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 06.05.12
 * Time: 9:48
 * To change this template use File | Settings | File Templates.
 */
class COrgChartController extends CBaseController {
    /**
     * Первый экшен, показывается при входе на страницу
     */
    public function actionIndex() {
        $this->addJSInclude("_core/jOrgChart/jquery.jOrgChart.js");
        $this->addJSInclude("_core/jOrgChart/jquery.jOrgChartEvents.js");
        $this->addJSInclude("_core/jUI/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jOrgChart/jquery.jOrgChart.css");
        $this->addCSSInclude("_core/prettify.css");
        if (CSession::isAuth()) {
            $this->addJSInlineInclude('
                jQuery(document).ready(function() {
                    $("#asuOrgChartContent").jOrgChart({
                        chartElement : "#asuOrgChart",
                        dragAndDrop  : true
                    });
                });
            ');
        } else {
            $this->addJSInlineInclude('
                jQuery(document).ready(function() {
                    $("#asuOrgChartContent").jOrgChart({
                        chartElement : "#asuOrgChart",
                        dragAndDrop  : false
                    });
                });
            ');
        }
    }
    /**
     * Обновление данных об оргструктуре аяксом через
     * перетаскивание
     */
    public function actionAjaxUpdate() {
        if (!CSession::isAuth()) {
            return true;
        }
        $sourceId = CRequest::getString("source");
        $destId = CRequest::getString("destination");

        $sourceId = substr($sourceId, strpos($sourceId, "_") + 1);
        $destId = substr($destId, strpos($destId, "_") + 1);

        $child = CStaffManager::getPersonById($sourceId);
        $parent = CStaffManager::getPersonById($destId);

        $child->setManager($parent);
        $child->save();

        $this->renderView(AJAX_VIEW);
    }
    /**
     * Построение организационной структуры вручную
     */
    public function actionManage() {
        if (!CSession::isAuth()) {
            return true;
        }

        // строим список преподавателей просто в виде списка
        $this->_smartyEnabled = true;
        CStaffManager::buildPersonHierarchy();
        CStaffManager::initPersonTypes();
        $persons = array();
        foreach (CStaffManager::getCachePerson()->getItems() as $item) {
            if ($item->hasPersonType(TYPE_PPS)) {
                if (is_null($item->getManager())) {
                    $persons[] = $item;
                }
            }
        }

        CTaxonomyManager::fullInit();
        $this->setData("persons", $persons);
        $this->renderView("_asuchart/manage.tpl");
    }
    /**
     * Работа с отдельным человеком - указание руководителя и роли
     *
     * @return bool
     */
    public function actionManagePerson() {
        if (!CSession::isAuth()) {
            return true;
        }

        $this->_smartyEnabled = true;
        $this->setData("person", CStaffManager::getPersonById(CRequest::getInt("id")));
        $this->renderView("_asuchart/managePerson.tpl");
    }
    /**
     * Сохранение изменений
     *
     * @return bool
     */
    public function actionManagePersonSave() {
        if (!CSession::isAuth()) {
            return true;
        }

        $person = CStaffManager::getPersonById(CRequest::getInt("id"));
        $manager = CStaffManager::getPersonById(CRequest::getInt("manager_id"));
        $role = CTaxonomyManager::getTerm(CRequest::getInt("department_role_id"));

        if (!is_null($manager)) {
            $person->setManager($manager);
        } else {
            $person->setManagerId(0);
        }

        if (!is_null($role)) {
            $person->setRole($role);
        }

        $person->save();

        $this->redirect("?action=manage");
    }
}
