<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 23.09.12
 * Time: 14:45
 * To change this template use File | Settings | File Templates.
 */

    require_once("../../core.php");
    mysql_query("SET NAMES UTF8");

    try{
        $controller = CRequest::getString("controller");
        $controllerClass = "C".ucwords($controller)."JSONController";
        if (!class_exists($controllerClass)) {
            throw new Exception("Не могу найти класс ".$controllerClass);
        }
        $controllerObject = new $controllerClass;
        $action = "action".CRequest::getString("action");
        if (!method_exists($controllerObject, $action)) {
            throw new Exception("Класс ".$controllerClass." не имеет метода ".$action);
        }
        $controllerObject->$action();
    } catch (Exception $e) {
        echo "Произошла ошибка: ", $e->getMessage();
    }