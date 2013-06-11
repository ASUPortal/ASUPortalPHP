<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.01.13
 * Time: 8:25
 * To change this template use File | Settings | File Templates.
 */
class CDiplom extends CActiveModel {
    protected $_table = TABLE_DIPLOMS;
    protected $_mark = null;
    protected $_student = null;
    protected $_person = null;
    protected $_previews = null;
    protected $_language = null;
    protected $_recomendationProtocol = null;
    protected $_confirmation = null;
    protected $_practPlace = null;
    protected $_reviewer = null;
    protected $_commission = null;
    private $_averageMark = null;

    public $aspire_recomendation = 0;

    protected function relations() {
        return array(
            "mark" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_mark",
                "storageField" => "study_mark",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getMark"
            ),
            "student" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_student",
                "storageField" => "student_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudent"
            ),
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "kadri_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "previews" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_previews",
                "relationFunction" => "getDiplomPreviews"
            ),
            "language" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_language",
                "storageField" => "foreign_lang",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getLanguage"
            ),
            "recomendationProtocol" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_recomendationProtocol",
                "storageField" => "protocol_2aspir_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
            ),
            "confirmation" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_confirmation",
                "storageField" => "diplom_confirm",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiplomConfirmation"
            ),
            "practPlace" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_practPlace",
                "storageField" => "pract_place_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getPracticePlace"
            ),
            "reviewer" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_reviewer",
                "storageField" => "recenz_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "commission" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_commission",
                "storageField" => "gak_num",
                "managerClass" => "CSABManager",
                "managerGetObject" => "getCommission"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "diplom_confirm" => "Утвержден",
            "dipl_name" => "Тема диплома",
            "pract_place" => "Место практики",
            "pract_place_id" => "Место практики",
            "kadri_id" => "Дипломный руководитель",
            "student_id" => "Студент",
            "group_id" => "Группа",
            "diplom_preview" => "Дата предзащиты",
            "date_act" => "Дата защиты",
            "foreign_lang" => "Иностранный язык",
            "protocol_2aspir_id" => "Протокол рекомендации в асипрантуру",
            "recenz_id" => "Рецензент",
            "study_mark" => "Оценка",
            "gak_num" => "Номер ГЭК",
            "comment" => "Комментарий",
            "diplom_number" => "Номер диплома",
            "diplom_regnum" => "Регистрационный номер",
            "diplom_regdate" => "Дата решения ГЭК",
            "diplom_issuedate" => "Дата выдачи",
            "session_start" => "Время начала защиты",
            "session_end" => "Время окончания защиты",
            "pages_diplom" => "Страниц в пояснительной записке",
            "pages_attach" => "Страниц чертежей (таблиц)",
            "aspire_recomendation" => "Рекомендован в аспирантуру",
            "average_mark" => "Средний балл"
        );
    }

    /**
     * @return CArrayList|null
     */
    protected function getDiplomPreviews() {
        if (is_null($this->_previews)) {
            $this->_previews = new CArrayList();
            /**
             * Если есть ключ диплома, то ищем по нему,
             * если нету, то ищем по студенту
             */
            foreach (CActiveRecordProvider::getWithCondition(TABLE_DIPLOM_PREVIEWS, "diplom_id = ".$this->getId())->getItems() as $item) {
                $preview = new CDiplomPreview($item);
                $this->_previews->add($preview->getId(), $preview);
            }
            if ($this->_previews->getCount() == 0) {
                if (!is_null($this->_student)) {
                    foreach (CActiveRecordProvider::getWithCondition(TABLE_DIPLOM_PREVIEWS, "student_id = ".$this->student->getId())->getItems() as $item) {
                        $preview = new CDiplomPreview($item);
                        $this->_previews->add($preview->getId(), $preview);
                    }
                }
            }
        }
        return $this->_previews;
    }

    /**
     * Дата последнего предпросмотра диплома в формате unix timestamp
     *
     * @return int
     */
    public function getLastPreviewDate() {
        $last = 0;
        foreach ($this->previews->getItems() as $preview) {
            if (strtotime($preview->date_preview) > $last) {
                $last = strtotime($preview->date_preview);
            }
        }
        return $last;
    }
    public function getAverageMarkComputed() {
    	if (is_null($this->_averageMark)) {
    		$this->_averageMark = 0;
    		$student = $this->student;
    		if (!is_null($student)) {
    			$query = new CQuery();
    			$query->select("n.*")
    			->from(TABLE_STUDENTS_ACTIVITY." as m")
    			->innerJoin(TABLE_MARKS." as n", "m.study_mark = n.id")
    			->condition("student_id = ".$student->getId()." AND study_mark in (1, 2, 3, 4) AND kadri_id = 380");
    			$items = $query->execute();
    			foreach ($items->getItems() as $item) {
    				if (mb_strtolower($item["name"]) == "удовлетворительно") {
    					$this->_averageMark += 3;
    				} elseif (mb_strtolower($item["name"]) == "хорошо") {
    					$this->_averageMark += 4;
    				} elseif (mb_strtolower($item["name"]) == "отлично") {
    					$this->_averageMark += 5;
    				} elseif (mb_strtolower($item["name"]) == "неудовлетворительно") {
    					$this->_averageMark += 2;
    				}
    			}
    			if ($items->getCount() > 0) {
    				$this->_averageMark = round(($this->_averageMark / ($items->getCount())), 2);
    			}
    		}    		
    	}
    	return $this->_averageMark;
    }
    public function isPerfect() {
        $result = false;
        $averageMark = 0;
        if (!is_null($this->average_mark)) {
        	$averageMark = $this->average_mark;
        } else {
        	$averageMark = $this->getAverageMarkComputed();
        }
        $mark = $this->mark;
        if (!is_null($mark)) {
        	if (mb_strtolower($mark->name) == "отлично" && $averageMark >= 4.75) {
        		$result = true;
        	}
        }
        return $result;
    }
}
