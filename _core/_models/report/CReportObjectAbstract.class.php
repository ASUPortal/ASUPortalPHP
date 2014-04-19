<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 19.04.14
 * Time: 20:45
 * To change this template use File | Settings | File Templates.
 */

abstract class CReportObjectAbstract extends CFormModel implements IReportObject{
    public function getParamsTemplate()
    {
        return "_reports/custom/".get_class($this)."/report.params.tpl";
    }

    public function getDataTemplate()
    {
        return "_reports/custom/".get_class($this)."/report.data.tpl";
    }

    abstract public function getReportData();

    public function useSmarty()
    {
        return true;
    }

    public function renderReport(){

    }
}