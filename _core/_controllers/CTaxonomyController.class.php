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
            $this->redirectNoAccess();
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
            $this->setData("taxonomy", $taxonomy);
            $this->renderView("_taxonomy/index.tpl");
        }
    }
    /**
     * Добавление терминов таксономии
     */
    public function actionAdd() {
        $taxonomy = CTaxonomyManager::getTaxonomy(CRequest::getInt("taxonomy_id"));
        $this->setData("taxonomy", $taxonomy);
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
        $this->renderView("_taxonomy/addTaxonomy.tpl");
    }
    public function actionSaveTaxonomy() {
        $taxonomy = CFactory::createTaxonomy();
        $taxonomy->name = CRequest::getString("name");
        $taxonomy->alias = CRequest::getString("alias");
        $taxonomy->save();

        $terms = explode(";", CRequest::getString("terms"));
        foreach ($terms as $term) {
            $t = CFactory::createTerm();
            $t->setValue(trim($term));
            $t->setTaxonomy($taxonomy);
            $t->save();
        }
        $this->redirect("?action=index&id=".$taxonomy->getId());
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
            $this->redirect("?action=index&id=".$term->getParentTaxonomy()->getId());
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
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy(CRequest::getInt("id"));
        $this->setData("taxonomy", $taxonomy);
        $this->renderView("_taxonomy/legacy.tpl");
    }
    public function actionEditLegacyTerm() {
        $term = CTaxonomyManager::getLegacyTerm(CRequest::getInt("id"), CRequest::getInt("taxonomy_id"));
        $this->setData("term", $term);
        $this->renderView("_taxonomy/legacyEdit.tpl");
    }
}
