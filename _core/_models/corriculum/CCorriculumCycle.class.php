<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 31.07.12
 * Time: 20:28
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumCycle extends CActiveModel {
    protected $_table = TABLE_CORRICULUM_CYCLES;
    protected $_corriculum = null;
    protected $_disciplines = null;
    protected $_basicDisciplines = null;
    protected $_variativeDisciplines = null;
    protected $_labors = null;
    protected $_hours = null;
    protected $_controls = null;

    protected function relations() {
        return array(
            "corriculum" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_corriculum",
                "storageField" => "corriculum_id",
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getCorriculum"
            ),
            "disciplines" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_disciplines",
                "storageTable" => TABLE_CORRICULUM_DISCIPLINES,
                "storageCondition" => "cycle_id=".$this->id." AND parent_id = 0",
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getDiscipline",
                "managerOrder" => "`ordering` asc"
            ),
            "basicDisciplines" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_disciplines",
                "storageTable" => TABLE_CORRICULUM_DISCIPLINES,
                "storageCondition" => "cycle_id=".$this->id." and type=1 and parent_id=0",
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getDiscipline"
            ),
            "variativeDisciplines" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_variativeDisciplines",
                "storageTable" => TABLE_CORRICULUM_DISCIPLINES,
                "storageCondition" => "cycle_id=".$this->id." and type=2 and parent_id=0",
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getDiscipline"
            ),
            "labors" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_labors",
                "relationFunction" => "getLabors"
            ),
            "hours" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_hours",
                "relationFunction" => "getHours"
            ),
            "controls" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_controls",
                "relationFunction" => "getControls"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "title_abbreviated" => "Краткое наименование",
            "number" => "Номер"
        );
    }
    /**
     * Дисциплины в виде списка
     *
     * @return array
     */
    public function getDisciplinesList() {
        $res = array();
        foreach ($this->disciplines->getItems() as $item) {
            $res[$item->id] = $item->number." ".$item->discipline->getValue();
        }
        return $res;
    }
    /**
     * Распределение трудоемкости по видам работ
     * во всех дисциплинах данного цикла
     *
     * @return CArrayList
     */
    protected  function getLabors() {
        $labors = new CArrayList();
        foreach ($this->disciplines->getItems() as $discipline) {
            foreach ($discipline->labors->getItems() as $labor) {
                $labors->add($labor->id, $labor->value + $labors->getItem($labor->id));
            }
        }
        return $labors;
    }
    /**
     * Распределение трудоемкости по семестрам
     *
     * @return CArrayList
     */
    protected function getHours() {
        $hours = new CArrayList();
        foreach ($this->disciplines->getItems() as $discipline) {
            foreach ($discipline->hours->getItems() as $hour) {
                $hours->add($hour->period, $hour->value + $hours->getItem($hour->period));
            }
        }
        return $hours;
    }
    /**
     * Распределение форм итогового контроля
     *
     * @return CArrayList
     */
    protected function getControls() {
        $controls = new CArrayList();
        foreach ($this->disciplines->getItems() as $discipline) {
            foreach ($discipline->controls->getItems() as $control) {
                $controls->add($control->id, $control->value + $controls->getItem($control->id));
            }
        }
        return $controls;
    }

    /**
     * Получить n-ую по счету дисциплину
     *
     * @param $number
     * @return CCorriculumDiscipline
     */
    public function getNthDiscipline($number) {
        $res = null;
        $i = 1;
        foreach ($this->disciplines->getItems() as $d) {
            if ($i == $number) {
                $res = $d;
                break;
            }
            $i++;
        }
        return $res;
    }
}
