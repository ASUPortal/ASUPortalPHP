<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.02.14
 * Time: 20:56
 * To change this template use File | Settings | File Templates.
 */

interface ISearchCatalogInterface{
    public function actionTypeAhead($lookup);
    public function actionGetItem($id);
    public function actionGetViewData();
    public function actionGetCreationActionUrl();
    public function actionGetCatalogProperties();
    public function actionGetDefaultCatalogProperties();
    public function actionGetObject($id);
}