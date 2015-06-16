<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.01.13
 * Time: 16:01
 * To change this template use File | Settings | File Templates.
 */
class CConfigurationController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            //$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Личные настройки");

        parent::__construct();
    }
    public function actionIndex() {
        $set = CActiveRecordProvider::getAllFromTable(TABLE_SETTINGS, "title asc");
        $settings = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $setting = new CSetting($item);
            $settings->add($setting->getId(), $setting);
        }
        $this->setData("settings", $settings);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_configuration/index.tpl");
    }
    public function actionAdd() {
        $setting = new CSetting();
        $setting->type = 1;
        $this->setData("types", array(
            1 => "Текстовое значение",
            2 => "PHP-код"
        ));
        $this->setData("setting", $setting);
        $this->renderView("_configuration/add.tpl");
    }
    public function actionEdit() {
        $setting = CSettingsManager::getSetting(CRequest::getInt("id"));
        $this->setData("types", array(
            1 => "Текстовое значение",
            2 => "PHP-код"
        ));
        /**
         * Попробуем каким-нибудь способом на основе параметров
         * определить список возможных значений для параметрам
         */
        if ($setting->params !== "") {
            eval('$values = '.$setting->params.";");
            $this->setData("values", $values);
        }
        $this->setData("setting", $setting);
        $this->renderView("_configuration/edit.tpl");
    }
    public function actionDelete() {
        $setting = CSettingsManager::getSetting(CRequest::getInt("id"));
        $setting->remove();
        $this->redirect("?action=index");
    }
    public function actionSave() {
        $setting = new CSetting();
        $setting->setAttributes(CRequest::getArray($setting::getClassName()));
        if ($setting->validate()) {
            $setting->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$setting->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $this->setData("types", array(
            1 => "Текстовое значение",
            2 => "PHP-код"
        ));
        $this->setData("setting", $setting);
        $this->renderView("_configuration/edit.tpl");
    }
}
