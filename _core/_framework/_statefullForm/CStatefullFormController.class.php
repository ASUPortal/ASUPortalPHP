<?php

/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.11.15
 * Time: 11:25
 */
define("FORM_BEAN", "bean");

abstract class CStatefullFormController extends CBaseController{
    private $_statefullBean = null;

    /**
     * CStatefullFormController constructor.
     */
    public function __construct() {
        // регистрируем плагин для smarty, который нам сильно поможет
        CStatefullFormSmartyPlugin::registerPlugins($this->getSmarty());
        // посмотрим, может нам событие прислали
        if (CRequest::getString("action") == "sendEvent") {
            $this->processEvent();
        } else if (CRequest::getString("action") == "submitForm") {
            $this->processFormSubmit();
        }

        // последний шаг - запускаем рендеринг
        $this->before_render();
        $this->render();
    }

    function __destruct() {
        CApp::getApp()->beans->serializeBean($this->getStatefullFormBean());
    }

    /**
     * Генератор названий обработчиков
     *
     * @param string $event
     * @param string $element
     * @return array
     */
    private function getHandlerNames($event = '', $element = '') {
        // получим основные названия обработчиков
        $handlers = array();
        $handlers[] = $event . '_' . $element;
        if (mb_strpos($element, '_') !== false) {
            $handlers[] = $event . '_' . CUtils::strLeft($element, '_');
        }
        $handlers[] = $event;
        // сгенерируем дополнительно полный список
        $result = array();
        foreach ($handlers as $handler) {
            $result[] = 'handle_before_' . $handler;
            $result[] = 'handle_' . $handler;
            $result[] = 'handle_after_' . $handler;
        }
        return $result;
    }

    /**
     * Обработка события сабмита формы
     */
    private function processFormSubmit() {
        /**
         * При сабмите приходят данные со всех форм, но сабмитится
         * за один раз только одна. В связи с этим, сохраняем
         * данные из всех форм в бин
         */
        foreach (CRequest::getGlobalRequestVariables() as $key=>$value) {
            if (is_array($value)) {
                self::getStatefullFormBean()->getElement($key)->setFormElementValues($value);
            }
        }
        /**
         * Обработаем форму, над которой выполняется действие
         */
        $element = CRequest::getString("element");
        $formData = CRequest::getArray($element);
        /**
         * В элементе формы могут быть старые ошибки валидации. Почистим их
         */
        self::getStatefullFormBean()->getElement($element)->setValidationErrors(array());
        /**
         * Теперь вызываем их по очереди
         */
        foreach ($this->getHandlerNames('submitForm', $element) as $handler) {
            if (method_exists($this, $handler)) {
                $this->$handler($formData, $element);
            }
        }
    }

    /**
     * Обработаем событие, которое нам пришло
     */
    private function processEvent() {
        $event = CRequest::getString('event');
        /**
         * Теперь попробуем их вызвать у текущего контроллера
         */
        $element = CRequest::getString('element');
        foreach ($this->getHandlerNames($event, $element) as $handler) {
            if (method_exists($this, $handler)) {
                $this->$handler();
            }
        }
    }

    /**
     * Стандартный обработчик изменения состояния элемента
     */
    private function handle_changeState() {
        $bean = $this->getStatefullFormBean();
        $element = CRequest::getString("element");
        $state = CRequest::getString("state");
        $bean->setElementState($element, $state);
    }


    /**
     * @return CStatefullFormBean
     */
    protected function getStatefullFormBean() {
        if (is_null($this->_statefullBean)) {
            $this->_statefullBean = new CStatefullFormBean();
            if (CRequest::getString(FORM_BEAN) != "") {
                $this->_statefullBean = CApp::getApp()->beans->getStatefullBean(CRequest::getString(FORM_BEAN));
            }
        }
        return $this->_statefullBean;
    }

    abstract function render();
    protected function before_render() {}
}