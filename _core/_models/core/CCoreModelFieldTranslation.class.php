<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 15:22
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelFieldTranslation extends CActiveModel{
    protected $_table = TABLE_CORE_MODLE_FIELD_TRANSLATIONS;
    protected $_language;

    public $field_id;

    protected function relations() {
        return array(
            "language" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_language",
                "relationFunction" => "getLanguage"
            )
        );
    }

    /**
     * @return CTerm
     */
    public function getLanguage() {
        if (is_null($this->_language)) {
            $tax = CTaxonomyManager::getLegacyTaxonomy("language");
            if (!is_null($tax)) {
                $this->_language = CTaxonomyManager::getLegacyTerm($this->language_id, $tax->getId());
            }
        }
        return $this->_language;
    }
}