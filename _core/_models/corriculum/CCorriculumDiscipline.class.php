<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 31.07.12
 * Time: 0:03
 * To change this template use File | Settings | File Templates.
 *
 * @property CTerm discipline
 * @property CCorriculumCycle cycle
 * @property CArrayList plans
 * @property CArrayList sections
 * @property CArrayList labors
 */
class CCorriculumDiscipline extends CActiveModel {
    protected $_table = TABLE_CORRICULUM_DISCIPLINES;
    protected $_discipline = null;
    protected $_cycle = null;
    protected $_children = null;
    protected $_labors = null;
    protected $_controls = null;
    protected $_hours = null;
    protected $_parent = null;
    protected $_competentions = null;
    protected $_plans = null;

    /**
     * Разнообразные публичные свойства
     */
    public $ordering = null;

    protected function relations() {
        return array(
            "discipline" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_discipline",
                "storageField" => "discipline_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
            ),
            "cycle" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_cycle",
                "storageField" => "cycle_id",
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getCycle"
            ),
            "parent" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_parent",
                "storageField" => "parent_id",
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getDiscipline"
            ),
            "children" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_children",
                "storageTable" => TABLE_CORRICULUM_DISCIPLINES,
                "storageCondition" => "parent_id=".$this->id,
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getDiscipline",
                "managerOrder" => "`ordering` asc"
            ),
            "labors" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_labors",
                "storageTable" => TABLE_CORRICULUM_DISCIPLINE_LABORS,
                "storageCondition" => "discipline_id=".$this->id,
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getLabor"
            ),
            "controls" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_controls",
                "storageTable" => TABLE_CORRICULUM_DISCIPLINE_CONTROLS,
                "storageCondition" => "discipline_id=".$this->id,
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getControl"
            ),
            "hours" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_hours",
                "storageTable" => TABLE_CORRICULUM_DISCIPLINE_HOURS,
                "storageCondition" => "discipline_id=".$this->id,
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getHour"
            ),
            "competentions" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_competentions",
                "storageTable" => TABLE_CORRICULUM_DISCIPLINE_COMPETENTIONS,
                "storageCondition" => "discipline_id=".$this->id,
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getCompetention"
            ),
            "plans" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_plans",
                "storageTable" => TABLE_WORK_PLANS,
                "storageCondition" => "corriculum_discipline_id=".$this->id." and _version_of = 0",
                "managerClass" => "CWorkPlanManager",
                "managerGetObject" => "getWorkplan"
            ),
            'sections' => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_CORRICULUM_DISCIPLINE_SECTIONS,
                "storageCondition" => "discipline_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => 'CCorriculumDisciplineSection'
            )
        );
    }
    public function attributeLabels() {
        return array(
            "discipline_id" => "Дисциплина",
            "ordering" => "Порядок в списке",
            "parent_id" => "Родительская дисциплина",
			"component_type_id" => "Вид компонента"
        );
    }
    /**
     * Трудоемкость по типу
     *
     * @param $key
     * @return CCorriculumDisciplineLabor
     */
    public function getLaborByType($key) {
        $res = null;
        foreach ($this->labors->getItems() as $labor) {
            if ($labor->type_id == $key) {
                $res = $labor;
            } elseif (!is_null($labor->type)) {
				if ($labor->type->getAlias() == $key) {
		        	$res = $labor;
				}
            }
        }
        if (is_null($res)) {
        	foreach ($this->sections->getItems() as $section) {
        		foreach ($section->labors->getItems() as $labor) {
        			if ($labor->type_id == $key) {
        				$res = $labor;
        			} elseif (!is_null($labor->type)) {
        				if ($labor->type->getAlias() == $key) {
        					$res = $labor;
        				}
        			}		
        		}
        	}
        }
        return $res;
    }
    /**
     * Форма контроля по форме
     *
     * @param $key
     * @return CCorriculumDisciplineControl
     */
    public function getControlByForm($key) {
        $res = null;
        foreach($this->controls->getItems() as $control) {
            if ($control->form_id == $key) {
                $res = $control;
            }
        }
        return $res;
    }

    /**
     * Общая трудоемкость
     *
     * @return int
     */
    public function getLaborValue() {
        $res = 0;
        foreach ($this->labors->getItems() as $labor) {
            $res += $labor->value;
        }
        return $res;
    }
    public function validationRules() {
        return array(
            "selected" => array(
                "discipline_id"
            )
        );
    }
}
