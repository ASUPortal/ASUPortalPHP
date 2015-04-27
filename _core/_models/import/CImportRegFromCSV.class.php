<?php

class CImportRegFromCSV implements IImportProvider{
    public function getImportFormName() {
        return "form.tpl";
    }

    public function getImportModel() {
        return new CImportRegFromCSVForm();
    }

    public function import(CFormModel $source) {
        $data = $source->getData();
        $isFirstRow = true;
        foreach ($data as $row) {
            if ($isFirstRow) {
                // это первая строка - id студента и рег. номер
                $isFirstRow = false;
            } else {
                // это все остальные строки - здесь уже студенты
                $student = CStaffManager::getStudent($row[0]);
                if (!is_null($student)) {
                	$diplom = CStaffManager::getDiplomByStudent($row[0]);
                    foreach ($row as $id=>$cell) {
                    	$diplom->student_id = $student->getId();
                    	$diplom->diplom_regnum = $row[1];
                    	$diplom->diplom_issuedate = date("d/m/Y", strtotime($source->created));
                    	$diplom->save();
                    }
                }
            }
        }
        return true;
    }

} 