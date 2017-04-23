<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.03.14
 * Time: 21:49
 * To change this template use File | Settings | File Templates.
 */

class CCorriculumCompetention extends CTerm {
    protected $_speciality = null;

    /**
     * @return CTerm|null
     */
    public function getSpeciality() {
        if (is_null($this->_speciality)) {
            $taxonomy = CTaxonomyManager::getLegacyTaxonomy(TAXONOMY_SPECIALITY);
            if (!is_null($taxonomy) and $this->alias != "") {
                $this->_speciality = $taxonomy->getTerm($this->alias);
            }
        }
        return $this->_speciality;
    }

    /**
     * @return mixed|string
     */
    public function getAlias() {
        if (!is_null($this->getSpeciality())) {
            return $this->getSpeciality()->getValue();
        }
        return "";
    }

    public function relations() {
        return array(
            "speciality" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_speciality",
                "relationFunction" => "getSpeciality"
            ),
        );
    }
}