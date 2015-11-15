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
        $this->render();
    }

    function __destruct() {
        CApp::getApp()->beans->serializeBean($this->getStatefullFormBean());
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
         * Соберем возможные обработчики события
         */
        $handlers = array();
        $handlers[] = 'submitForm_' . $element;
        if (mb_strpos($element, '_') !== false) {
            $handlers[] = 'submitForm_' . CUtils::strLeft($element, '_');
        }
        $handlers[] = 'submitForm';
        /**
         * В элементе формы могут быть старые ошибки валидации. Почистим их
         */
        self::getStatefullFormBean()->getElement($element)->setValidationErrors(array());
        /**
         * Теперь вызываем их по очереди
         */
        foreach ($handlers as $handler) {
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
         * Соберем возможные обработчики события
         * 1. Если задан id, то попробуем найти handle_event_id
         * 2. В остальных случаях handle_event
         */
        $handlers = array();
        if (CRequest::getInt("id") > 0) {
            $handlers[] = 'handle_'.$event.'_'.CRequest::getInt("id");
        }
        $handlers[] = 'handle_'.$event;
        /**
         * Теперь попробуем их вызвать у текущего контроллера
         */
        foreach ($handlers as $handler) {
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
}