<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 06.02.15
 * Time: 19:41
 */

class CIndPlanPersonLoadTable_PrintRight extends CAbstractPrintClassField{
    public function getFieldName()
    {
        return "Нагрузка в индивидуальном плане, весенний семестр";
    }

    public function getFieldDescription()
    {
        return "Используется при печати индивидуального плана, принимает параметр planId с Id плана";
    }

    public function getParentClassField()
    {

    }

    public function getFieldType()
    {
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
        $result = array();
        $load = CIndPlanManager::getLoad(CRequest::getInt("planId"));
        $studyLoad = $load->getStudyLoadTable();
        $table = $studyLoad->getTable(true);
        // это только весенний семестр семестр
        $preparedData = array();
        foreach ($table as $row) {
            if ($load->isSeparateContract()) {
                // если есть бюджет-контракт, то суммируем их
                $preparedRow = array();
                $preparedRow[0] = $row[0];
                for ($i = 1; $i <= 17; $i++) {
                    $preparedRow[$i] = $row[($i * 2)] + $row[($i * 2 - 1)];
                }
                $preparedData[] = $preparedRow;
            } else {
                // нет разделения на бюджет-контракт, копируем
                $preparedData[] = $row;
            }
        }
        // это описатель для весеннего семестра, вместо названия ставим номер
        foreach ($preparedData as $preparedRow) {
            $row = array();
            $fact = 0;
            foreach ($preparedRow as $index=>$value) {
                if ($index == 0) {
                    $row[0] = count($result) + 1;
                }
                if ($index > 7) {
                    $row[] = $value;
                    // дублируем итого в факт
                    // убран факт из шаблона
                    /*if ($index == 15) {
                        $row[] = $value;
                    }*/
                }
            }
            $row[] = "";
            $result[] = $row;
        }
        return $result;
    }
}