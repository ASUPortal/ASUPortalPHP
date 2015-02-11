<?php

class CSearchCatalogPreviewCommission extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup)
    {
        $result = array();
        // комиссии по предзащите дипломов. показываем только комиссии этого года
        $query = new CQuery();
        $query->select("distinct(comm.id) as id, comm.name as name")
            ->from(TABLE_DIPLOM_PREVIEW_COMISSIONS." as comm")
            ->condition("comm.name like '%".$lookup."%' and comm.date_act between ".date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start))." and ".date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_end)))
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $comm = new CDiplomPreviewComission(new CActiveRecord($item));
            $value = $comm->name;
            if (!is_null($comm->secretar)) {
                $value .= " ".$comm->secretar->fio;
            }
            $result[$comm->getId()] = $value;
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        // комиссии по предзащите дипломов
        $commission = CSABManager::getPreviewCommission($id);
        if (!is_null($commission)) {
            $value = $commission->name;
            if (!is_null($commission->secretar)) {
                $value .= " ".$commission->secretar->fio;
            }
            $result[$commission->getId()] = $value;
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        $query = new CQuery();
        // комиссии по предзащите дипломов. показываем только комиссии этого года
        $query->select("comm.*")
        ->from(TABLE_DIPLOM_PREVIEW_COMISSIONS." as comm")
        ->condition("comm.date_act between '".date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start))."' and '".date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_end))."'");
        foreach ($query->execute()->getItems() as $ar) {
            $comm = new CDiplomPreviewComission(new CActiveRecord($ar));
            $value = $comm->name;
            if (!is_null($comm->secretar)) {
                $value .= " ".$comm->secretar->fio;
            }
            $result[$comm->getId()] = $value;
        }
        return $result;
    }
    public function actionGetCreationActionUrl()
    {
    	return WEB_ROOT."_modules/_diploms/preview_comm.php?action=add";
    }
}