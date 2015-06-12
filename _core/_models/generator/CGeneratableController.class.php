<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.08.13
 * Time: 12:35
 * To change this template use File | Settings | File Templates.
 */

class CGeneratableController extends CFormModel {
    public $controllerName = "CSomeObjectsController";
    public $controllerFile = "_modules/_somemodule/index.php";
    public $controllerPath = "_core/_controllers/";
    public $pageTitle = "Управление какими-то объектами";
    public $modelName = "CSomeObject";
    public $modelTable = "TABLE_SOME_OBJECTS";
    public $modelManager = "CBaseManager";
    public $modelManagerGetter = "getSomeObject";
    public $viewPath = "_someobjects";
    public $viewIndexTitle = "Заголовок страницы списка";
    public $viewIndexNoObjects = "Нет объектов для отображения";
    public $viewObjectSingleName = "русское название объекта";
    public $viewObjectSingleNameRP = "русского названия объекта";
    public $modelGenerate = 0;
    public $modelPath = "_core/_models/";


    public function getParams() {
        return parent::getItems();
    }
}