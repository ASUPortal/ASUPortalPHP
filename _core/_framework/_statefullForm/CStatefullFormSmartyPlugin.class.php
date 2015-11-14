<?php

/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.11.15
 * Time: 12:05
 */
class CStatefullFormSmartyPlugin {
    /**
     * Регистрируем плагины в переданном экземпляре smarty
     *
     * @param Smarty $smarty
     */
    public static function registerPlugins(Smarty $smarty) {
        $smarty->registerPlugin('block', 'sf_changeState', array('CStatefullFormSmartyPlugin', 'StatefullForm_ChangeState'));
        $smarty->registerPlugin('block', 'sf_showIfVisible', array('CStatefullFormSmartyPlugin', 'StatefullForm_ShowIfVisible'));
        $smarty->registerPlugin('block', 'sf_showIfEditable', array('CStatefullFormSmartyPlugin', 'StatefullForm_ShowIfEditable'));
        $smarty->registerPlugin('function', 'sf_toggleVisible', array('CStatefullFormSmartyPlugin', 'StatefullForm_ToggleVisible'));
        $smarty->registerPlugin('function', 'sf_toggleEdit', array('CStatefullFormSmartyPlugin', 'StatefullForm_ToggleEdit'));
        $smarty->registerPlugin('function', 'sf_showByDefault', array('CStatefullFormSmartyPlugin', 'StatefullForm_ShowByDefault'));
    }

    /**
     * Иконка перехода в режим редактирования
     *
     * @param array $params
     * @throws Exception
     */
    public static function StatefullForm_ToggleEdit($params = array()) {
        self::checkParams($params);

        $bean = self::getStatefullFormBean($params);
        $element = self::getElementId($params);

        if ($bean->getElement($element)->isEdit()) {
            $content = '<i class="icon-remove">&nbsp;</i>';
            $params['state'] = 'show';
            $params['element'] = $element;

            echo self::StatefullForm_ChangeState($params, $content);
        } else {
            $content = '<i class="icon-pencil">&nbsp;</i>';
            $params['state'] = 'edit';
            $params['element'] = $element;

            echo self::StatefullForm_ChangeState($params, $content);
        }
    }

    /**
     * Указанный элемент по умолчанию получает статус show
     * Не всем элементам это надо
     *
     * @param array $params
     * @throws Exception
     */
    public static function StatefullForm_ShowByDefault($params = array()) {
        self::checkParams($params);

        $bean = self::getStatefullFormBean($params);
        $element = self::getElementId($params);

        if (is_null($bean->getElement($element)->getState())) {
            $bean->getElement($element)->setShow(true);
        }
    }

    /**
     * Переключить элемень $params['element'] с помощью +/- в состояние
     * вижу/не вижу
     *
     * @param array $params
     * @throws Exception
     */
    public static function StatefullForm_ToggleVisible($params = array()) {
        self::checkParams($params);

        $bean = self::getStatefullFormBean($params);
        $element = self::getElementId($params);

        if ($bean->getElement($element)->isShow()) {
            $content = '<i class="icon-minus"></i>';
            $params['state'] = 'hide';
        } else {
            $content = '<i class="icon-plus"></i>';
            $params['state'] = 'show';
        }

        echo self::StatefullForm_ChangeState($params, $content);
    }

    public static function StatefullForm_ShowIfVisible($params = array(), $content = '') {
        self::checkParams($params);

        $bean = self::getStatefullFormBean($params);
        $element = self::getElementId($params);

        if ($bean->getElement($element)->isShow()) {
            return $content;
        }
        return '';
    }

    public static function StatefullForm_ShowIfEditable($params = array(), $content = '') {
        self::checkParams($params);

        $bean = self::getStatefullFormBean($params);
        $element = self::getElementId($params);

        if ($bean->getElement($element)->isEdit()) {
            return $content;
        }
        return '';
    }

    private static function getElementId($params = array()) {
        if (!array_key_exists('element', $params)) {
            throw new Exception('Не задан параметр element, к которому отправляется событие');
        }
        return $params['element'];
    }

    /**
     * Проверить, что все необходимые параметры заданы
     *
     * @param $params
     * @throws Exception
     */
    private static function checkParams($params) {
        /**
         * Проверим, все ли параметры заданы
         */
        if (!array_key_exists('bean', $params)) {
            throw new Exception('Не задан параметр bean');
        }
        if (!is_a($params['bean'], 'CStatefullFormBean')) {
            throw new Exception('Bean не экземпляр класса CStatefullFormBean');
        }
        if (!array_key_exists('element', $params)) {
            throw new Exception('Не задан параметр element, к которому отправляется событие');
        }
    }

    /**
     * Получить используемый в форме бин
     *
     * @param array $params
     * @return CStatefullFormBean
     * @throws Exception
     */
    private static function getStatefullFormBean($params = array()) {
        if (!array_key_exists('bean', $params)) {
            throw new Exception('Не задан параметр bean');
        }
        if (!is_a($params['bean'], 'CStatefullFormBean')) {
            throw new Exception('Bean не экземпляр класса CStatefullFormBean');
        }
        //
        return $params['bean'];
    }

    /**
     * Создать ссылку из параметров
     *
     * @param array $params
     * @return string
     */
    private static function createReference($params = array()) {
        $reference = WEB_ROOT;
        if (array_key_exists('address', $params)) {
            $reference = $params['address'];
            unset($params['address']);
        }
        $pairs = array();
        foreach ($params as $key=>$value) {
            if (is_a($value, 'CModel')) {
                $pairs[] = 'id=' . $value->getId();
            } elseif (is_a($value, 'CStatefullBean')) {
                $pairs[] = $key . '=' . $value->getBeanId();
            } else {
                $pairs[] = $key.'='.$value;
            }
        }
        $reference .= '?'.implode("&", $pairs);
        return $reference;
    }

    public static function StatefullForm_ChangeState($params = array(), $content = '') {
        self::checkParams($params);

        $params['action'] = 'sendEvent';
        $params['event'] = 'changeState';

        $link = '<a href="'.self::createReference($params).'">'.$content.'</a>';

        return $link;
    }
}