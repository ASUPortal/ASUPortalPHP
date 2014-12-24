<?php
/**
 * Created by PhpStorm.
 * User: ABarmin
 * Date: 04.12.2014
 * Time: 10:09
 */

class CImportMarksFromCSV implements IImportProvider{
    public function getImportFormName() {
        return "form.tpl";
    }

    public function getImportModel() {
        return new CImportMarksFromCSVForm();
    }


    public function import(CFormModel $source) {
        $data = $source->getData();
        $markIds = array();
        $isFirstRow = true;
        foreach ($data as $row) {
            if ($isFirstRow) {
                // это первая строка - в не идентификаторы оценок
                $isFirstRow = false;
                $markIds = $row;
            } else {
                // это все остальные строки - здесь уже студенты
                $student = CStaffManager::getStudent($row[0]);
                if (!is_null($student)) {
                    $isFirstCol = true;
                    foreach ($row as $id=>$cell) {
                        if (!$isFirstCol) {
                            if ($cell != "") {
                                // если не пустая, то берем дисциплину
                                $subjectId = $markIds[$id];
                                $subject = CTaxonomyManager::getDiscipline($subjectId);
                                if (!is_null($subject)) {
                                    // если дисциплина есть, то происходит маппинг оценок
                                    $marks = array(
                                        "2" => "4",
                                        "3" => "3",
                                        "4" => "2",
                                        "5" => "1",
                                    );
                                    // создаем запись об оценке
                                    $activity = new CStudentActivity();
                                    $activity->subject_id = $subject->getId();
                                    $activity->kadri_id = $source->person;
                                    $activity->student_id = $student->getId();
                                    $activity->date_act = date("Y-m-d", strtotime($source->created));
                                    if (mb_strlen($cell) == 2 || strlen($cell) == 2) {
                                        // это курсовой
                                        $cell = mb_substr($cell, 0, 1);
                                        $activity->study_act_id = 43;
                                        if (array_key_exists($cell, $marks)) {
                                            $activity->study_mark = $marks[$cell];
                                        }
                                    }elseif (array_key_exists($cell, $marks)) {
                                        // это экзамен
                                        $activity->study_act_id = 1;
                                        $activity->study_mark = $marks[$cell];
                                    } else {
                                        // это зачет
                                        $activity->study_act_id = 2;
                                        $activity->study_mark = 5;
                                    }
                                    $activity->save();
                                }
                            }
                        } else {
                            // пропускаем первую ячейку - в ней идентификатор студента
                            $isFirstCol = false;
                        }
                    }
                }
            }
        }
        return true;
    }

} 