<?php
/**
 * Description of CCorriculum
 *
 * @author TERRAN
 */
class CCorriculum extends CActiveModel{
    protected $_table = TABLE_CORRICULUMS;
    protected $_direction = null;
    protected $_profile = null;
    protected $_edForm = null;
    protected $_qualification = null;
    protected $_cycles = null;
    protected $_practices = null;

    protected function relations() {
        return array(
            "direction" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_direction",
                "storageField" => "direction_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getSpeciality"
            ),
            "profile" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_profile",
                "storageField" => "profile_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "educationForm" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_edForm",
                "storageField" => "form_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getEductionForm"
            ),
            "qualification" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_qualification",
                "storageField" => "qualification_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "cycles" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_cycles",
                "storageTable" => TABLE_CORRICULUM_CYCLES,
                "storageCondition" => "corriculum_id = " . $this->id,
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getCycle"
            ),
            "practices" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_practices",
                "storageTable" => TABLE_CORRICULUM_PRACTICES,
                "storageCondition" => "corriculum_id = " . $this->id,
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getPractice"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "direction_id" => "Направление",
            "basic_education_id" => "Начальное образование",
            "profile_id" => "Специализация",
            "duration" => "Длительность обучения",
            "qualification_id" => "Квалификация выпускника",
            "speciality_id" => "Специальность",
            "title" => "Название плана",
            "description" => "Описание"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title",
                "duration"
            ),
            "selected" => array(
                "direction_id",
                "profile_id",
                "qualification_id"
            )
        );
    }
    public static function getClassName() {
        return __CLASS__;
    }

    /**
     * Получить цикл по краткому названию
     *
     * @param $name
     * @return CCorriculumCycle
     */
    public function getCycleByAbbreviatedName($name) {
        $cycle = null;
        foreach ($this->cycles->getItems() as $c) {
            if ($c->title_abbreviated == $name) {
                $cycle = $c;
            }
        }
        return $cycle;
    }

    /**
     * Практика по сокращенному имени
     *
     * @param $name
     * @return CCorriculumPractice
     */
    public function getPracticeByAbbreviatedName($name) {
        $p = null;
        foreach ($this->practices->getItems() as $pr) {
            if ($pr->alias == $name) {
                $p = $pr;
            }
        }
        return $p;
    }
}
