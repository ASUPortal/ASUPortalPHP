<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 06.02.15
 * Time: 19:11
 */

class CIndPlanPersonLoadTable_PrintLeft extends CAbstractPrintClassField{
    public function getFieldName()
    {
        return "Нагрузка в индивидуальном плане, осенний семестр";
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
        // это только осенний семестр
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
        // это описатель для осеннего семестра, в убираем все столбцы
        // после 7. Жаль, что это не php 5.6, там можно было бы через array_filter
        // удобно в замыкании это сделать
        foreach ($preparedData as $preparedRow) {
            $row = array();
            $fact = 0;
            foreach ($preparedRow as $index=>$value) {
                if ($index <= 7) {
                    $row[] = $value;
                    if ($index > 1 && $index < 7) {
                        $fact += $value;
                    }
                }
            }
            $row[8] = $fact;
            $result[] = $row;
        }
        return $result;
    }
}