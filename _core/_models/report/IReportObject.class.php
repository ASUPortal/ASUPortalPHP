<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 19.04.14
 * Time: 15:59
 * To change this template use File | Settings | File Templates.
 */

interface IReportObject {
    public function getParamsTemplate();
    public function getDataTemplate();
    public function getReportData();
    public function useSmarty();
}