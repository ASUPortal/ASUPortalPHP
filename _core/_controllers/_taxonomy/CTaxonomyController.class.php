<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 08.05.12
 * Time: 23:55
 * To change this template use File | Settings | File Templates.
 *
 * Контроллер таксономий
 */
class CTaxonomyController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $action = CRequest::getString("action");
            if ($action == "") {
                $action = "index";
            }
            if (!in_array($action, $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        } else {
            if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_NO_ACCESS) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление таксономиями");

        parent::__construct();
    }
    /**
     * Просмотр терминов выбранной таксономии
     */
    public function actionIndex(){
        $taxonomy = CTaxonomyManager::getTaxonomy(CRequest::getInt("id"));
        if (is_null($taxonomy)) {
            $taxonomies = CTaxonomyManager::getTaxonomiesObjectList();
            /**
             * Получаем список унаследованных таксономий
             */
            $legacy = CTaxonomyManager::getLegacyTaxonomiesObjects();
            $this->setData("legacy", $legacy);
            $this->setData("taxonomies", $taxonomies);
            $this->renderView("_taxonomy/list.tpl");
        } else {
            $this->addJQInlineInclude('
                $("#taxonomy_id").change(function(){
                    if ($("#taxonomy_id").val() != 0) {
                        window.location.href = "?action=index&id=" + $("#taxonomy_id").val();
                    }
                });
            ');
            $this->addJSInclude(JQUERY_UI_JS_PATH);
            $this->addCSSInclude(JQUERY_UI_CSS_PATH);
            $this->setData("taxonomy", $taxonomy);
            $this->renderView("_taxonomy/index.tpl");
        }
    }
    /**
     * Добавление терминов таксономии
     */
    public function actionAdd() {
        $taxonomy = CTaxonomyManager::getTaxonomy(CRequest::getInt("taxonomy_id"));
        $term = new CTerm();
        $term->taxonomy_id = $taxonomy->getId();
        $this->setData("term", $term);
        $this->renderView("_taxonomy/add.tpl");
    }
    /**
     * Сохранение отправленных данных
     */
    public function actionSave() {
        $taxonomy = CTaxonomyManager::getTaxonomy(CRequest::getInt("taxonomy_id"));
        $term = CFactory::createTerm();
        $term->setTaxonomy($taxonomy);
        $term->setValue(CRequest::getString("name"));
        $term->setAlias(CRequest::getString("alias"));
        if (CRequest::getInt("id") != 0) {
            $term->setId(CRequest::getInt("id"));
        }
        $term->save();

        $this->redirect("?action=index&id=".$taxonomy->getId());
    }
    public function actionDelete() {
        $term = CTaxonomyManager::getTerm(CRequest::getInt("id"));
        $taxonomy = $term->getParentTaxonomy();
        $term->remove();
        $this->redirect("?action=index&id=".$taxonomy->getId());
    }
    public function actionAddTaxonomy(){
        $taxonomy = new CTaxonomy();
        $this->setData("taxonomy", $taxonomy);
        $this->renderView("_taxonomy/addTaxonomy.tpl");
    }
    public function actionSaveTaxonomy() {
        $taxonomy = new CTaxonomy();
        $taxonomy->setAttributes(CRequest::getArray($taxonomy::getClassName()));
        $taxonomy->save();

        $terms = CRequest::getArray($taxonomy::getClassName());
        $terms = explode(chr(13), $terms["terms"]);
        foreach ($terms as $term) {
            $t = CFactory::createTerm();
            $t->setValue(trim($term));
            $t->setTaxonomy($taxonomy);
            $t->save();
        }
        if ($this->continueEdit()) {
            $this->redirect("?action=index&id=".$taxonomy->getId());
        } else {
            $this->redirect("?action=index");
        }
    }
    public function actionDeleteTaxonomy() {
        $taxonomy = CTaxonomyManager::getTaxonomy(CRequest::getInt("id"));
        if (!is_null($taxonomy)) {
            foreach ($taxonomy->getTerms()->getItems() as $term) {
                $term->remove();
            }
            $taxonomy->remove();
        }
        $this->redirect("?action=index");
    }
    public function actionEditTerm() {
        $term = new CTerm();
        if (CRequest::getInt("id") != 0) {
            $term = CTaxonomyManager::getTerm(CRequest::getInt("id"));
        }
        $this->setData("term", $term);
        $this->renderView("_taxonomy/editTerm.tpl");
    }
    public function actionSaveTerm() {
        $term = new CTerm();
        $term->setAttributes(CRequest::getArray(CTerm::getClassName()));
        if ($term->validate()) {
            $term->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=editTerm&id=".$term->getId());
            } else {
                $this->redirect("?action=index&id=".$term->getParentTaxonomy()->getId());
            }
        }
        $this->setData("term", $term);
        $this->renderView("_taxonomy/editTerm.tpl");
    }

    /**
     * Просмотр таксономии из унаследованного справочника
     */
    public function actionLegacy() {
        $this->addJQInlineInclude('
                $("#taxonomy_id").change(function(){
                    if ($("#taxonomy_id").val() != 0) {
                        window.location.href = "?action=legacy&id=" + $("#taxonomy_id").val();
                    }
                });
            ');
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy(CRequest::getInt("id"));
        $this->setData("taxonomy", $taxonomy);
        $this->renderView("_taxonomy/legacy.tpl");
    }
    public function actionEditLegacyTerm() {
        $term = CTaxonomyManager::getLegacyTerm(CRequest::getInt("id"), CRequest::getInt("taxonomy_id"));
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy(CRequest::getInt("taxonomy_id"));
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "index.php?action=legacy&id=".$taxonomy->getId(),
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->setData("term", $term);
        $this->setData("taxonomy", $taxonomy);
        $this->renderView("_taxonomy/legacyEdit.tpl");
    }
    public function actionAddLegacyTerm() {
        $term = new CTerm();
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy(CRequest::getInt("taxonomy_id"));
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "index.php?action=legacy&id=".$taxonomy->getId(),
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $term->taxonomy_id = $taxonomy->getId();
        $term->setTable($taxonomy->getTableName());
        $this->setData("term", $term);
        $this->setData("taxonomy", $taxonomy);
        $this->renderView("_taxonomy/legacyAdd.tpl");
    }
    public function actionDeleteLegacyTerm() {
        $term = CTaxonomyManager::getLegacyTerm(CRequest::getInt("id"), CRequest::getInt("taxonomy_id"));
        $term->remove();
        $this->redirect("?action=legacy&id=".CRequest::getInt("taxonomy_id"));
    }
    public function actionSaveLegacyTerm() {
        $term = new CTerm();
        //
        $postData = CRequest::getArray($term::getClassName());
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy($postData['taxonomy_id']);
        // $taxonomy = CTaxonomyManager::getLegacyTaxonomy($term->taxonomy_id);
        $term->setTable($taxonomy->getTableName());
        $term->setAttributes(CRequest::getArray($term::getClassName()));
        /**
         * А теперь перекомпановываем запись для работы с унаследованными
         * таксономиями
         */
        $term->getRecord()->unsetItem("taxonomy_id");
        if ($term->validate()) {
            $term->save();
            $this->redirect("?action=legacy&id=".$taxonomy->getId());
            return true;
        }
        $this->setData("term", $term);
        $this->renderView("_taxonomy/legacyEdit.tpl");
    }
    public function actionSaveLegacyTaxonomy() {
        $taxonomy = new CTaxonomyLegacy();
        $taxonomy->setAttributes(CRequest::getArray($taxonomy::getClassName()));
        if ($taxonomy->validate()) {
            $taxonomy->save();
            $this->redirect("?action=legacy&id=".$taxonomy->getId());
            return true;
        }
        $this->setData("taxonomy", $taxonomy);
        $this->renderView("_taxonomy/legacy.taxonomy.edit.tpl");
    }
    public function actionAddLegacyTaxonomy() {
        $taxonomy = new CTaxonomyLegacy();
        $this->setData("taxonomy", $taxonomy);
        $this->renderView("_taxonomy/legacy.taxonomy.add.tpl");
    }
    public function actionDeleteLegacyTaxonomy() {
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy(CRequest::getInt("id"));
        $taxonomy->remove();
        $this->redirect("?action=index");
    }
    public function actionImportTerms() {
        $arr = CRequest::getArray(CTaxonomy::getClassName());
        $taxonomy = CTaxonomyManager::getTaxonomy($arr["id"]);
        $terms = $arr["terms"];
        $terms = explode("\n", $terms);
        foreach ($terms as $t) {
            if (trim($t) != "") {
                $term = new CTerm();
                $term->setTaxonomy($taxonomy);
                $term->setValue($t);
                $term->save();
            }
        }
        $this->redirect("index.php?action=index&id=".$taxonomy->getId());
    }
}
