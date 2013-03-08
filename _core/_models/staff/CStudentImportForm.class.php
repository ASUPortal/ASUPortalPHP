<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 30.01.13
 * Time: 20:37
 * To change this template use File | Settings | File Templates.
 */
class CStudentImportForm extends CFormModel {
    private $_results;
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "file" => "Файл для импорта"
        );
    }
    public function fieldsProperty() {
        return array(
            "file" => array(
                "type" => FIELD_UPLOADABLE,
                "upload_dir" => CORE_CWD.DIRECTORY_SEPARATOR."library".DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR
            )
        );
    }

    /**
     * Возложим работу по импорту студентов на саму функцию
     */
    public function importStudents() {
        /**
         * Открываем файл с csv
         */
        $fileHandler = fopen($this->getFileName(), "r");
        $csv = array();
        $firstLine = true;
        while ($csv = fgetcsv($fileHandler)) {
            if (!$firstLine) {
                $this->processStudent($csv);
            } else {
                $firstLine = false;
            }
        }
        return $this->getResults();
    }

    /**
     * Получим имя файла. Проще отдельную функцию на это написать))
     *
     * @return string
     */
    private function getFileName() {
        $properties = $this->fieldsProperty();
        return $properties["file"]["upload_dir"].$this->file;
    }

    /**
     * Сама обработка студентов
     *
     * @param array $source
     */
    private function processStudent(array $source) {
        /**
         * Попробуем искать студента. Искать будем по ФИО
         */
        $fio = $source[1]." ".$source[2]." ".$source[3];
        $student = CStaffManager::getStudent($fio);
        /**
         * Если студент был, то хорошо, если не был, то создаем
         * и добавляем в список добавленных
         */
        if (is_null($student)) {
            $student = new CStudent();
            $student->fio = $fio;
            $added = new CArrayList();
            if ($this->getResults()->hasElement("Добавлен")) {
                $added = $this->getResults()->getItem("Добавлен");
            }
            $added->add($student->getName(), $student->getName());
            $this->getResults()->add("Добавлен", $added);
        }
        $updated = new CArrayList();
        if ($this->getResults()->hasElement("Обновлен")) {
            $updated = $this->getResults()->getItem("Обновлен");
        }
        $fields = new CArrayList();
        if ($updated->hasElement($student->getName())) {
            $fields = $updated->getItem($student->getName());
        }
        /**
         * Теперь у нас есть студент. В любом случае есть))
         * Проверяем, может что-то не совпадает
         */
        $needSave = false;
        /**
         * Год окончания школы
         */
        if ($source[4] !== "") {
            if (mb_strtolower($student->year_school_end) !== mb_strtolower($source[4])) {
                $needSave = true;
                $student->year_school_end = $source[4];
                $fields->add("Год окончания предыдущего образовательно учреждения", $source[4]);
            }
        }
        /**
         * Предыдущее образовательное учреждение
         */
        if ($source[5] !== "") {
            if (is_null($student->primaryEducation)) {
                $needSave = true;
                $term = $this->getTerm("primary_education", $source[5]);
                $student->primary_education_type_id = $term->getId();
                $fields->add("Предыдущее образовательно учреждение", $source[5]);
            } elseif (mb_strtolower($student->primaryEducation->getValue()) !== mb_strtolower($source[5])) {
                $needSave = true;
                $term = $this->getTerm("primary_education", $source[5]);
                $student->primary_education_type_id = $term->getId();
                $fields->add("Предыдущее образовательно учреждение", $source[5]);
            }
        }
        /**
         * Год поступления в вуз
         */
        if ($source[6] !== "") {
            if (mb_strtolower($student->year_university_start) !== $source[6]) {
                $needSave = true;
                $student->year_university_start = $source[6];
                $fields->add("Год поступления в ВУЗ", $source[6]);
            }
        }
        /**
         * Форма обучения, на которую поступал
         */
        if ($source[7] !== "") {
            if (is_null($student->secondaryEducationStartType)) {
                $needSave = true;
                $term = $this->getTerm("education_form", $source[7]);
                $student->education_form_start = $term->getId();
                $fields->add("Форма обучения, на которую поступали", $source[7]);
            } elseif (mb_strtolower($student->secondaryEducationStartType->getValue()) !== mb_strtolower($source[7])) {
                $needSave = true;
                $term = $this->getTerm("education_form", $source[7]);
                $student->education_form_start = $term->getId();
                $fields->add("Форма обучения, на которую поступали", $source[7]);
            }
        }
        /**
         * Форма обучения, которую заканчивает
         */
        if ($source[8] !== "") {
            if (is_null($student->secondaryEducationEndType)) {
                $needSave = true;
                $term = $this->getTerm("education_form", $source[8]);
                $student->education_form_end = $term->getId();
                $fields->add("Форма обучения, которую заканчивает", $source[8]);
            } elseif (mb_strtolower($student->secondaryEducationEndType->getValue()) !== mb_strtolower($source[8])) {
                $needSave = true;
                $term = $this->getTerm("education_form", $source[8]);
                $student->education_form_end = $term->getId();
                $fields->add("Форма обучения, которую заканчивает", $source[8]);
            }
        }
        /**
         * Пол
         */
        if ($source[9] !== "") {
            if (is_null($student->gender)) {
                $needSave = true;
                $term = $this->getTerm("gender", $source[9]);
                $student->gender_id = $term->getId();
                $fields->add("Пол", $source[9]);
            } elseif (mb_strtolower($student->gender->getValue()) !== mb_strtolower($source[9])) {
                $needSave = true;
                $term = $this->getTerm("gender", $source[9]);
                $student->gender_id = $term->getId();
                $fields->add("Пол", $source[9]);
            }
        }
        /**
         * Номер телефона
         */
        if ($source[10] !== "") {
            if (mb_strtoupper($student->telephone) !== mb_strtoupper($source[10])) {
                $needSave = true;
                $student->telephone = $source[10];
                $fields->add("Телефон", $source[10]);
            }
        }
        /**
         * Место текущей работы
         */
        if ($source[11] !== "") {
            if (mb_strtoupper($student->work_current) !== mb_strtoupper($source[11])) {
                $needSave = true;
                $student->work_current = $source[11];
                $fields->add("Место текущей работы", $source[11]);
            }
        }
        /**
         * Примечания
         */
        if ($source[12] !== "") {
            if (mb_strtoupper($student->comment) !== mb_strtoupper($source[12])) {
                $needSave = true;
                $student->comment = $source[12];
                $fields->add("Примечание", $source[12]);
            }
        }
        /**
         * Номер группы
         */
        if ($source[13] !== "") {
            $source[13] = str_replace(" ", "-", $source[13]);
            $group = CStaffManager::getStudentGroup($source[13]);
            if (is_null($student->getGroup())) {
                if (!is_null($group)) {
                    $student->group_id = $group->getId();
                    $needSave = true;
                    $fields->add("Группа", $source[13]);
                }
            } elseif (mb_strtoupper($student->getGroup()->getName()) !== mb_strtoupper($source[13])) {
                if (!is_null($group)) {
                    $student->group_id = $group->getId();
                    $needSave = true;
                    $fields->add("Группа", $source[13]);
                }
            }
        }
        /**
         * Место предполагаемой работы
         */
        if ($source[14] !== "") {
            if (mb_strtoupper($student->work_proposed) !== mb_strtoupper($source[14])) {
                $needSave = true;
                $student->work_proposed = $source[14];
                $fields->add("Место предполагаемой работы", $source[14]);
            }
        }
        if ($needSave) {
            $student->save();
        }
        $updated->add($student->getName(), $fields);
        $this->getResults()->add("Обновлен", $updated);
    }

    /**
     * Результаты
     *
     * @return CArrayList
     */
    private function getResults() {
        if (is_null($this->_results)) {
            $this->_results = new CArrayList();
        }
        return $this->_results;
    }

    /**
     * Ищет термин в указанной таксономии. Если не находит - создает
     *
     * @param $taxonomy
     * @param $key
     * @return CTerm
     */
    private function getTerm($taxonomyName, $key) {
        if ($taxonomyName == "education_form") {
            $forms = new CArrayList();
            /**
             * Пересортируем формы обучения в другом порядке, чтобы ключом
             * было название
             */
            foreach (CTaxonomyManager::getCacheEducationForms()->getItems() as $form) {
                $forms->add(mb_strtolower($form->getValue()), $form);
            }
            if ($forms->hasElement(mb_strtolower($key))) {
                $term = $forms->getItem(mb_strtolower($key));
            } else {
                $term = new CTerm();
                $term->setTable(TABLE_EDUCATION_FORMS);
                $term->setValue($key);
                $term->save();
                CTaxonomyManager::getCacheEducationForms()->add($term->getId(), $term);
            }
        } elseif ($taxonomyName == "gender") {
            $genders = new CArrayList();
            foreach (CTaxonomyManager::getCacheGenders()->getItems() as $gender) {
                $genders->add(mb_strtoupper($gender->getValue()), $gender);
            }
            if ($genders->hasElement(mb_strtoupper($key))) {
                $term = $genders->getItem(mb_strtoupper($key));
            }
        } else {
            $taxonomy = CTaxonomyManager::getTaxonomy($taxonomyName);
            $term = $taxonomy->getTerm($key);
            if (is_null($term)) {
                $term = new CTerm();
                $term->taxonomy_id = $taxonomy->getId();
                $term->setValue($key);
                $term->save();
                $taxonomy->addTerm($term);
            }
        }
        return $term;
    }
}
