<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Aleksandr Barmin
 * Date: 12.10.14
 * Time: 13:29
 * 
 * URL: http://mydesignstudio.ru/
 * mailto: abarmin@mydesignstudio.ru
 * twitter: @alexbarmin
 */

class CFlowController extends CBaseController{
    private static $_alreadyInstantiated = false;

    public function __construct() {
        // если нет параметра запуска флоу, то ведем себя как обычный контроллер
        if (CRequest::getString("flow") == "") {
            parent::__construct();
            return true;
        }
        // костыль, чтобы не допустить повторной инициализации для
        // флоу-контроллеров
        if (self::$_alreadyInstantiated) {
            return true;
        }
        // передаем управление другому объекту
        $controllerClass = CRequest::getString("targetClass");
        $controllerMethod = "action".CRequest::getString("targetMethod");
        self::$_alreadyInstantiated = true;
        self::$_useFlowController = true;

        $controller = new $controllerClass();
        $controller->$controllerMethod();
    }

    /**
     * Показать диалог для выбора
     *
     * @param CArrayList $items
     * @param $targetClass
     * @param $targetMethod
     * @param bool $multiple
     */
    public function showPickList(CArrayList $items, $targetClass, $targetMethod, $multiple = false) {
        $this->setData("targetClass", $targetClass);
        $this->setData("targetMethod", $targetMethod);
        $this->setData("multiple", $multiple);
        $this->setData("items", $items);
        $this->renderView("_flow/pickList.tpl");
    }

    /**
     * Получить идентификаторы выбранных в диалоге объектов
     *
     * @return CArrayList
     */
    public function getSelectedInPickListDialog() {
        $selected = new CArrayList();
        $items = CRequest::getArray("selected");
        foreach ($items as $i) {
            $selected->add($i, $i);
        }
        return $selected;
    }

    /**
     * Переадресация
     *
     * @param $url
     * @param null $message
     */
    public function redirect($url, $message = null) {
        if (!self::$_useFlowController) {
            parent::redirect($url, $message);
        }
        echo json_encode(array(
            "action" => "redirect",
            "url" => $url,
            "message" => $message
        ));
    }

    /**
     * Передать управление в следующий объект
     *
     * @param $targetClass
     * @param $targetMethod
     */
    public function redirectNextAction($targetClass, $targetMethod) {
        echo json_encode(array(
            "action" => "redirectNextAction",
            "targetClass" => $targetClass,
            "targetMethod" => $targetMethod
        ));
    }
}