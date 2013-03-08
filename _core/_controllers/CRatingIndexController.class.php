<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 24.09.12
 * Time: 21:25
 * To change this template use File | Settings | File Templates.
 */
class CRatingIndexController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление доступом пользователей");

        parent::__construct();
    }
    public function actionIndex() {
        if (CRequest::getInt("year") == 0) {
            $year = CUtils::getCurrentYear();
        } else {
            $year = CTaxonomyManager::getYear(CRequest::getInt("year"));
        }
        $indexes = CRatingManager::getRatingIndexesByYear($year);
        $this->addJSInclude("_modules/_rating/ratingIndex.js");
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->setData("indexes", $indexes);
        $this->setData("year", $year);
        $this->renderView("_rating/index/index.tpl");
    }
    public function actionAdd() {
        if (CRequest::getInt("id", CRatingIndex::getClassName()) == 0) {
            $index = CFactory::createRatingIndex();
            $index->year_id = CUtils::getCurrentYear()->getId();
        } else {
            $index = CRatingManager::getRatingIndex(CRequest::getInt("id", CRatingIndex::getClassName()));
        }
        $this->addJSInclude("_modules/_rating/index.js");
        $this->setData("index", $index);
        $this->renderView("_rating/index/add.tpl");
    }
    public function actionEdit() {
        $index = new CRatingIndex();
        if (CRequest::getInt("id") != 0) {
            $index = CRatingManager::getRatingIndex(CRequest::getInt("id"));
        }
        $forms = array();
        foreach ($index->getIndexValues()->getItems() as $key=>$value) {
            $form = new CRatingValueForm();
            $form->id = $key;
            $form->value = $value->getValue();
            $form->evaluate_method = $value->evaluate_method;
            $form->evaluate_code = $value->evaluate_code;
            $form->edit_title = 1;
            $form->title = $value->getTitle();
            if ($value->isFromTaxonomy()) {
                $form->edit_title = 0;
            }
            $forms[] = $form;
        }
        $this->addJSInclude("_modules/_rating/index.js");
        $this->setData("evaluation_methods", array(
            1 => "SQL-запрос",
            2 => "PHP код"
        ));
        $this->setData("forms", $forms);
        $this->setData("index", $index);
        $this->renderView("_rating/index/edit.tpl");
    }
    public function actionSave() {
        $index = new CRatingIndex();
        $index->setAttributes(CRequest::getArray(CRatingIndex::getClassName()));
        if ($index->validate()) {
            if (array_key_exists(CRatingValueForm::getClassName(), $_POST)) {
                $values = $_POST[CRatingValueForm::getClassName()];
                foreach ($values as $key=>$value) {
                    if (strpos($key, "unsaved_") !== false) {
                        // здесь несохраненное значение из словаря
                        $key = substr($key, 8);
                        $itemValue = new CRatingIndexValue();
                        $itemValue->index_id = $index->id;
                        $itemValue->fromTaxonomy = 1;
                        $itemValue->title = $key;
                        $itemValue->value = $value['value'];
                        $itemValue->evaluate_method = $value['evaluate_method'];
                        $itemValue->evaluate_code = $value['evaluate_code'];
                        $itemValue->save();
                    } elseif(is_numeric($key)) {
                        // это уже ранее сохраненное значение
                        $itemValue = CRatingManager::getRatingIndexValue($key);
                        if (is_null($itemValue)) {
                            $itemValue = new CRatingIndexValue();
                        }
                        $itemValue->value = $value['value'];
                        if (array_key_exists("edit_title", $value)) {
                            if ($value['edit_title'] == "1") {
                                if ($value['title'] !== "") {
                                    $itemValue->title = $value['title'];
                                }
                            }
                        }
                        $itemValue->evaluate_method = $value['evaluate_method'];
                        $itemValue->evaluate_code = $value['evaluate_code'];
                        $itemValue->save();
                    } elseif (strpos($key, "new_") !== false) {
                        // это добавленное вручную значение
                        $itemValue = new CRatingIndexValue();
                        $itemValue->index_id = $index->id;
                        $itemValue->fromTaxonomy = 0;
                        $itemValue->title = $value['title'];
                        $itemValue->value = $value['value'];
                        $itemValue->evaluate_method = $value['evaluate_method'];
                        $itemValue->evaluate_code = $value['evaluate_code'];
                        $itemValue->save();
                    } else {
                        // это какая-то ошибка
                    }
                }
            }
            $index->save();
            $this->redirect("?action=index");
        }
        $this->setData("form", new CRatingValueForm());
        $this->setData("index", $index);
        $this->renderView("_rating/index/add.tpl");
    }
    public function actionDelete() {
        $index = CRatingManager::getRatingIndex(CRequest::getInt("id"));
        if (!is_null($index)) {
            foreach ($index->getIndexValues()->getItems() as $value) {
                if (!is_null($value->id)) {
                    $value->remove();
                }
            }
            $index->remove();
        }
        $this->redirect("?action=index");
    }
    public function actionDeleteValue() {
        $value = CRatingManager::getRatingIndexValue(CRequest::getInt("id"));
        if (!is_null($value)) {
            $index = $value->parentIndex->id;
            $value->remove();
            $this->redirect("?action=edit&id=".$index);
            return true;
        }
        $this->redirect("?action=index");
    }
    public function actionImport() {
        $thisYear = new CArrayList();
        // импортируем показатели должностей
        $index = new CRatingIndex();
        $index->title = "Должность";
        $index->manager_class = "CTaxonomyManager";
        $index->manager_method = "getPosts()";
        $index->person_method = "getPost()";
        $index->year_id = CUtils::getCurrentYear()->getId();
        $index->isMultivalue = 0;
        $index->save();
        $thisYear->add($index->getId(), $index);

        foreach (CActiveRecordProvider::getAllFromTable("spr_dolzhnost")->getItems() as $item) {
            $post = CTaxonomyManager::getPostById($item->getItemValue("id"));
            if (!is_null($post)) {
                $value = new CRatingIndexValue();
                $value->index_id = $index->id;
                $value->fromTaxonomy = 1;
                $value->title = $post->getId();
                $value->value = $item->getItemValue("rate");
                $value->save();
            }
        }

        // показатели размножаем на все года
        foreach (CTaxonomyManager::getYearsList() as $key=>$value) {
            $year = CTaxonomyManager::getYear($key);
            if ($year->getId() !== $index->year_id) {
                $newIndex = new CRatingIndex();
                $newIndex->title = $index->title;
                $newIndex->manager_class = $index->manager_class;
                $newIndex->manager_method = $index->manager_method;
                $newIndex->person_method = $index->person_method;
                $newIndex->year_id = $year->getId();
                $newIndex->isMultivalue = $index->isMultivalue;
                $newIndex->save();

                foreach ($index->getIndexValues()->getItems() as $value) {
                    $newValue = new CRatingIndexValue();
                    $newValue->index_id = $newIndex->getId();
                    $newValue->fromTaxonomy = $value->fromTaxonomy;
                    $newValue->title = $value->title;
                    $newValue->value = $value->value;
                    $newValue->save();
                }
            }
        }

        // звания
        $index = new CRatingIndex();
        $index->title = "Звание";
        $index->manager_class = "CTaxonomyManager";
        $index->manager_method = "getTitles()";
        $index->year_id = CUtils::getCurrentYear()->getId();
        $index->isMultivalue = 0;
        $index->person_method = "getTitle()";
        $index->save();

        $thisYear->add($index->getId(), $index);

        foreach (CActiveRecordProvider::getAllFromTable("spr_zvanie")->getItems() as $item) {
            $post = CTaxonomyManager::getTitle($item->getItemValue("id"));
            if (!is_null($post)) {
                $value = new CRatingIndexValue();
                $value->index_id = $index->id;
                $value->fromTaxonomy = 1;
                $value->title = $post->getId();
                $value->value = $item->getItemValue("rate");
                $value->save();
            }
        }

        // показатели размножаем на все года
        foreach (CTaxonomyManager::getYearsList() as $key=>$value) {
            $year = CTaxonomyManager::getYear($key);
            if ($year->getId() !== $index->year_id) {
                $newIndex = new CRatingIndex();
                $newIndex->title = $index->title;
                $newIndex->manager_class = $index->manager_class;
                $newIndex->manager_method = $index->manager_method;
                $newIndex->year_id = $year->getId();
                $newIndex->isMultivalue = $index->isMultivalue;
                $newIndex->person_method = $index->person_method;
                $newIndex->save();

                foreach ($index->getIndexValues()->getItems() as $value) {
                    $newValue = new CRatingIndexValue();
                    $newValue->index_id = $newIndex->getId();
                    $newValue->fromTaxonomy = $value->fromTaxonomy;
                    $newValue->title = $value->title;
                    $newValue->value = $value->value;
                    $newValue->save();
                }
            }
        }

        // научно-методическая работа
        $taxonomy = new CTaxonomy();
        $taxonomy->name = "Виды научно-методической и учебной работы";
        $taxonomy->alias = "scientificWork";
        $taxonomy->save();

        $index = new CRatingIndex();
        $index->title = "Научно-методическая и учебная работа";
        $index->manager_class = "CTaxonomyManager";
        $index->manager_method = 'getTaxonomy("scientificWork")->getTerms()';
        $index->year_id = CUtils::getCurrentYear()->getId();
        $index->isMultivalue = 1;
        $index->save();

        $thisYear->add($index->getId(), $index);

        foreach (CActiveRecordProvider::getAllFromTable("spr_nauch_met_uch_rab")->getItems() as $item) {
            $term = new CTerm();
            $term->taxonomy_id = $taxonomy->getId();
            $term->name = $item->getItemValue("name");
            $term->save();

            $value = new CRatingIndexValue();
            $value->index_id = $index->id;
            $value->fromTaxonomy = 1;
            $value->title = $term->getId();
            $value->value = $item->getItemValue("rate");
            $value->save();
        }

        // показатели размножаем на все года
        foreach (CTaxonomyManager::getYearsList() as $key=>$value) {
            $year = CTaxonomyManager::getYear($key);
            if ($year->getId() !== $index->year_id) {
                $newIndex = new CRatingIndex();
                $newIndex->title = $index->title;
                $newIndex->manager_class = $index->manager_class;
                $newIndex->manager_method = $index->manager_method;
                $newIndex->year_id = $year->getId();
                $newIndex->isMultivalue = $index->isMultivalue;
                $newIndex->save();

                foreach ($index->getIndexValues()->getItems() as $value) {
                    $newValue = new CRatingIndexValue();
                    $newValue->index_id = $newIndex->getId();
                    $newValue->fromTaxonomy = $value->fromTaxonomy;
                    $newValue->title = $value->title;
                    $newValue->value = $value->value;
                    $newValue->save();
                }
            }
        }

        // вычеты
        $taxonomy = new CTaxonomy();
        $taxonomy->name = "Виды вычетов";
        $taxonomy->alias = "takeouts";
        $taxonomy->save();

        $index = new CRatingIndex();
        $index->title = "Вычеты";
        $index->manager_class = "CTaxonomyManager";
        $index->manager_method = 'getTaxonomy("takeouts")->getTerms()';
        $index->year_id = CUtils::getCurrentYear()->getId();
        $index->isMultivalue = 1;
        $index->save();

        $thisYear->add($index->getId(), $index);

        foreach (CActiveRecordProvider::getAllFromTable("spr_vichet")->getItems() as $item) {
            $term = new CTerm();
            $term->taxonomy_id = $taxonomy->getId();
            $term->name = $item->getItemValue("name");
            $term->save();

            $value = new CRatingIndexValue();
            $value->index_id = $index->id;
            $value->fromTaxonomy = 1;
            $value->title = $term->getId();
            $value->value = $item->getItemValue("rate");
            $value->save();
        }

        // показатели размножаем на все года
        foreach (CTaxonomyManager::getYearsList() as $key=>$value) {
            $year = CTaxonomyManager::getYear($key);
            if ($year->getId() !== $index->year_id) {
                $newIndex = new CRatingIndex();
                $newIndex->title = $index->title;
                $newIndex->manager_class = $index->manager_class;
                $newIndex->manager_method = $index->manager_method;
                $newIndex->year_id = $year->getId();
                $newIndex->isMultivalue = $index->isMultivalue;
                $newIndex->save();

                foreach ($index->getIndexValues()->getItems() as $value) {
                    $newValue = new CRatingIndexValue();
                    $newValue->index_id = $newIndex->getId();
                    $newValue->fromTaxonomy = $value->fromTaxonomy;
                    $newValue->title = $value->title;
                    $newValue->value = $value->value;
                    $newValue->save();
                }
            }
        }
    }
}
