<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 15:43
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelFieldTranslationsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            //$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление переводом полей моделей данных");

        parent::__construct();
    }
    public function actionAdd() {
        $t = new CCoreModelFieldTranslation();
        $t->field_id = CRequest::getInt("id");
        $this->setData("translation", $t);
        $this->renderView("_core/translation/add.tpl");
    }
    public function actionEdit() {
        $t = CCoreObjectsManager::getCoreModelFieldTranslation(CRequest::getInt("id"));
        $this->setData("translation", $t);
        $this->renderView("_core/translation/edit.tpl");
    }
    public function actionSave() {
        $t = new CCoreModelFieldTranslation();
        $t->setAttributes(CRequest::getArray($t::getClassName()));
        if ($t->validate()) {
            $t->save();
            if ($this->continueEdit()) {
                $this->redirect("translations.php?action=edit&id=".$t->getId());
            } else {
                $this->redirect("fields.php?action=edit&id=".$t->field_id);
            }
        }
        $this->setData("translation", $t);
        $this->renderView("_core/translation/edit.tpl");
    }
    public function actionDelete() {

    }
}