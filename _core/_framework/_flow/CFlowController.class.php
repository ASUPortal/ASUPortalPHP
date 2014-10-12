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
    private static $_statefullBean = null;
    private static $_statefullBeanId = "";

    public function __construct() {
        // инициализируем бин
        if (CRequest::getString("beanId") != "") {
            self::$_statefullBeanId = CRequest::getString("beanId");
        }
        // если пришли параметры инициализации бина - инициализируем бин
        if (is_array(CRequest::getArray("beanData"))) {
            $params = CRequest::getArray("beanData");
            foreach ($params as $key=>$value) {
                self::getStatefullBean()->add($key, $value);
            }
        }

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

        // включим смарти, нельзя без него
        $this->_smartyEnabled = true;

        // передаем управление другому объекту
        $controllerClass = CRequest::getString("targetClass");
        $controllerMethod = "action".CRequest::getString("targetMethod");
        self::$_alreadyInstantiated = true;
        self::$_useFlowController = true;

        $controller = new $controllerClass();
        $controller->$controllerMethod();
    }

    /**
     * Текущий бин состояния
     *
     * @return CStatefullBean
     */
    public static function getStatefullBean() {
        if (is_null(self::$_statefullBean)) {
            self::$_statefullBean = new CStatefullBean();
            if (self::$_statefullBeanId != "") {
                $bean = CApp::getApp()->beans->getStatefullBean(self::$_statefullBeanId);
                if (!is_null($bean)) {
                    self::$_statefullBean = $bean;
                }
            }
        }
        return self::$_statefullBean;
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
        CApp::getApp()->beans->serializeBean(self::getStatefullBean());
        echo json_encode(array(
            "action" => "redirect",
            "url" => $url,
            "message" => $message,
            "beanId" => self::getStatefullBean()->getBeanId()
        ));
    }

    /**
     * Передать управление в следующий объект
     *
     * @param $targetClass
     * @param $targetMethod
     */
    public function redirectNextAction($targetClass, $targetMethod) {
        CApp::getApp()->beans->serializeBean(self::getStatefullBean());
        echo json_encode(array(
            "action" => "redirectNextAction",
            "targetClass" => $targetClass,
            "targetMethod" => $targetMethod,
            "beanId" => self::getStatefullBean()->getBeanId()
        ));
    }

    /**
     * Отрисовать представление, результаты будут переданы указанному
     * классу и его методу
     *
     * @param $view
     * @param $targetClass
     * @param $targetMethod
     */
    public function renderView($view, $targetClass = "", $targetMethod = "") {
        if ($targetClass != "") {
            $this->setData("targetClass", $targetClass);
        }
        if ($targetMethod != "") {
            $this->setData("targetMethod", $targetMethod);
        }
        CApp::getApp()->beans->serializeBean(self::getStatefullBean());
        $this->setData("beanId", self::getStatefullBean()->getBeanId());

        parent::renderView($view);
    }
}