<?php
/**
 * Created by PhpStorm.
 * User: ABarmin
 * Date: 04.12.2014
 * Time: 10:00
 */

class CImportController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Импорт данных");

        parent::__construct();
    }
    public function actionIndex() {
        $this->renderView("_import/home/index.tpl");
    }
    public function actionForm() {
        $providerClass = CRequest::getString("provider");
        /**
         * @var $provider IImportProvider
         */
        $provider = new $providerClass();
        $formView = "_import/providers/".get_class($provider)."/".$provider->getImportFormName();
        $this->setData("formView", $formView);
        $this->setData("form", $provider->getImportModel());
        $this->setData("provider", get_class($provider));
        $this->renderView("_import/home/form.tpl");
    }
    public function actionImport() {
        $providerClass = CRequest::getString("provider");
        /**
         * @var $provider IImportProvider
         */
        $provider = new $providerClass();
        $providerModel = $provider->getImportModel();
        $providerModel->setAttributes(CRequest::getArray($providerModel::getClassName()));
        if ($providerModel->validate()) {
            if ($provider->import($providerModel)) {
                $this->redirect("?action=complete");
            }
            return true;
        }
        $formView = "_import/providers/".get_class($provider)."/".$provider->getImportFormName();
        $this->setData("formView", $formView);
        $this->setData("form", $providerModel);
        $this->setData("provider", get_class($provider));
        $this->renderView("_import/home/form.tpl");
    }
    public function actionComplete() {
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        $this->renderView("_import/home/complete.tpl");
    }
} 