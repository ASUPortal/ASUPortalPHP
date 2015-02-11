<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.02.14
 * Time: 21:06
 * To change this template use File | Settings | File Templates.
 */

class CSearchCatalogSABCommission extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup)
    {
        $result = array();
        // комиссии по защите дипломов. показываем только комиссии этого года
        $query = new CQuery();
        $query->select("distinct(comm.id) as id, comm.title as name")
            ->from(TABLE_SAB_COMMISSIONS." as comm")
            ->condition("comm.title like '%".$lookup."%' and year_id=".CUtils::getCurrentYear()->getId())
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $comm = new CSABCommission(new CActiveRecord($item));
            $value = $comm->title;
            if (!is_null($comm->manager)) {
                $value .= " ".$comm->manager->getName();
            }
            if (!is_null($comm->secretar)) {
                $value .= " (".$comm->secretar->getName().")";
            }
            $diplom = CStaffManager::getDiplom(CRequest::getInt("diplom_id"));
            if (!is_null($diplom)) {
                $cnt = 0;
                foreach ($comm->diploms->getItems() as $d) {
                    if (strtotime($diplom->date_act) == strtotime($d->date_act)) {
                        $cnt++;
                    }
                }
                $value .= " ".$cnt;
            }
            $result[$comm->getId()] = $value;
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        // комиссии по защите дипломов
        $commission = CSABManager::getCommission($id);
        if (!is_null($commission)) {
            $value = $commission->title;
            if (!is_null($commission->manager)) {
                $value .= " ".$commission->manager->getName();
            }
            if (!is_null($commission->secretar)) {
                $value .= " (".$commission->secretar->getName().")";
            }
            $diplom = CStaffManager::getDiplom(CRequest::getInt("diplom_id"));
            if (!is_null($diplom)) {
                $cnt = 0;
                foreach ($commission->diploms->getItems() as $d) {
                    if (strtotime($diplom->date_act) == strtotime($d->date_act)) {
                        $cnt++;
                    }
                }
                $value .= " ".$cnt;
            }
            $result[$commission->getId()] = $value;
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        // комиссии по защите дипломов. показываем только комиссии этого года
        foreach (CActiveRecordProvider::getWithCondition(TABLE_SAB_COMMISSIONS, "year_id=".CUtils::getCurrentYear()->getId())->getItems() as $ar) {
            $comm = new CSABCommission($ar);
            $value = $comm->title;
            if (!is_null($comm->manager)) {
                $value .= " ".$comm->manager->getName();
            }
            if (!is_null($comm->secretar)) {
                $value .= " (".$comm->secretar->getName().")";
            }
            $diplom = CStaffManager::getDiplom(CRequest::getInt("diplom_id"));
            if (!is_null($diplom)) {
                $cnt = 0;
                foreach ($comm->diploms->getItems() as $d) {
                    if (strtotime($diplom->date_act) == strtotime($d->date_act)) {
                        $cnt++;
                    }
                }
                $value .= " ".$cnt;
            }
            $result[$comm->getId()] = $value;
        }
        return $result;
    }
    public function actionGetCreationActionUrl()
    {
        // TODO: Implement actionGetCreationActionUrl() method.
    }
}