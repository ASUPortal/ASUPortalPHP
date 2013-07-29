<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 22.12.12
 * Time: 11:29
 * To change this template use File | Settings | File Templates.
 */
class CSettingsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Личные настройки");

        parent::__construct();
    }
    public function actionIndex() {
        $settings = new CUserSettings();
        $settings->user_id = CSession::getCurrentUser()->getId();
        if (!is_null(CSession::getCurrentUser()->getPersonalSettings())) {
            $settings = CSession::getCurrentUser()->getPersonalSettings();
        }
        $sizes = array(
            5, 6, 7, 8
        );
        $this->setData("sizes", $sizes);
        $this->setData("settings", $settings);
        $this->renderView("_settings/index.tpl");
    }
    public function actionSave() {
        $checkboxes = array(
            "dashboard_enabled",
            "dashboard_show_birthdays",
            "dashboard_show_messages",
            "dashboard_show_all_tasks",
            "dashboard_check_messages"
        );
        $settings = new CUserSettings();
        $settings->setAttributes(CRequest::getArray($settings::getClassName()));
        foreach ($checkboxes as $box) {
            if (!array_key_exists($box, CRequest::getArray($settings::getClassName()))) {
                $settings->$box = 0;
            }
        }
        if ($settings->validate()) {
            $settings->save();
            $this->redirect("?action=index");
        }
    }
}
