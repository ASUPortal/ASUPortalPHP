<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 06.02.15
 * Time: 22:38
 */

abstract class CAbstractSearchCatalog extends CComponent implements ISearchCatalogInterface{
    public $properties = array();

    public function actionGetCatalogProperties()
    {
        return array();
    }

    public function actionGetDefaultCatalogProperties()
    {
        return array();
    }

}