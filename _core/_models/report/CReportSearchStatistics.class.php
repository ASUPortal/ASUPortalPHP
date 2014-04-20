<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 19.04.14
 * Time: 16:00
 * To change this template use File | Settings | File Templates.
 */

class CReportSearchStatistics extends CReportObjectAbstract {
    public $start;
    public $end;

    function __construct()
    {
        $this->end = new DateTime();
        $this->start = new DateTime();
        $this->start->modify("-1 year");

        $this->start = $this->start->format("d.m.Y");
        $this->end = $this->end->format("d.m.Y");
    }

    public function attributeLabels()
    {
        return array(
            "start" => "Дата начала",
            "end" => "Дата окончания"
        );
    }


    public function getReportData()
    {
        $result = array();
        // получаем данные о статистике
        // за промежуток времени
        $condition = "time_stamp BETWEEN '".date("Y-m-d", strtotime($this->start))."' AND '".date("Y-m-d", strtotime($this->end))."'";
        $condition .= " and (s.q_string like '%action=search%'";
        $condition .= " or s.q_string like '%action=getGlobalSearchSubform%'";
        $condition .= " or s.q_string like '%action=LookupGetCreationDialog%'";
        $condition .= " or s.q_string like '%action=GlobalSearch%'";
        $condition .= " or s.q_string like '%action=LookupGetDialog%'";
        $condition .= " or s.q_string like '%action=LookupGetItem%'";
        $condition .= " or s.q_string like '%action=LookupTypeAhead%' )";
        $query = new CQuery(CApp::getApp()->getDbLogConnection());
        $select = "month(s.time_stamp) as t_stamp_m, year(s.time_stamp) as t_stamp_y, count(id) as cnt";
        $select .= ", concat(month(s.time_stamp), '.', year(s.time_stamp)) as t_stamp";
        $query->select($select)
            ->from("stats as s")
            ->condition($condition)
            ->group("t_stamp_m")
            ->order("t_stamp_y desc, t_stamp_m desc");
        $result = $query->execute()->getItems();
        return $result;
    }
}