<?php
/**
 * Description of CCorriculum
 *
 * @author TERRAN
 *
 * @property CTerm direction
 * @property int direction_id
 * @property int qualification_id
 * @property int form_id
 * @property CArrayList cycles
 */
class CCorriculum extends CActiveModel{
    protected $_table = TABLE_CORRICULUMS;
    protected $_direction = null;
    protected $_profile = null;
    protected $_edForm = null;
    protected $_qualification = null;
    protected $_cycles = null;
    protected $_practices = null;
    protected $_speciality_direction = null;
    protected $_attestations = null;

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
            ),
            "speciality_direction" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_speciality_direction",
                "storageField" => "speciality_direction_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "attestations" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_attestations",
                "storageTable" => TABLE_CORRICULUM_ATTESTATIONS,
                "storageCondition" => "corriculum_id = " . $this->id,
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getAttestation"
            ),
        	"nisChairman" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_nis_chairman_id",
        		"storageField" => "nis_chairman_id",
        		"managerClass" => "CStaffManager",
        		"managerGetObject" => "getPerson"
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
            "description" => "Описание",
            "final_exam_title" => "Название итогового экзамена",
        	"load_classroom" => "Аудиторная нагрузка",
        	"load_total" => "Общая нагрузка",
            "form_id" => "Форма обучения",
			"load_as_fullday" => "Срок обучения по очной форме",
			"speciality_direction_id" => "Специальность/направление по уч. плану",
        	"nis_chairman_id" => "Председатель НМС",
        	"direction.name" => "Направление",
        	"term.name" => "Профиль",
        	"educ_form.name" => "Форма обучения"
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
                "qualification_id",
                "form_id",
                "speciality_direction_id"
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
     * Все дисциплины учебного плана в алфавитном порядке
     *
     * @return CArrayList
     */
    public function getDisciplines() {
        $disciplines = new CArrayList();
        $query = new CQuery();
        $query->select("d.*")
            ->from(TABLE_CORRICULUM_DISCIPLINES." as d")
            ->innerJoin(TABLE_CORRICULUM_CYCLES." as cycle", "d.cycle_id = cycle.id")
            ->condition("cycle.corriculum_id = ".$this->getId())
            ->innerJoin(TABLE_DISCIPLINES." as discipline", "discipline.id = d.discipline_id")
            ->order("discipline.name");
        foreach ($query->execute() as $ar) {
            $discipline = new CCorriculumDiscipline(new CActiveRecord($ar));
            $disciplines->add($discipline->getId(), $discipline);
        }
        return $disciplines;
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
