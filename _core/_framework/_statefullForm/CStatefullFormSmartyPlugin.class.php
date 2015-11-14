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
        // $smarty->registerPlugin("block", "sf_sendEventLink", array("CStatefullFormSmartyPlugin", "StatefullForm_SendEventLink"));
        $smarty->registerPlugin('block', 'sf_changeState', array('CStatefullFormSmartyPlugin', 'StatefullForm_ChangeState'));
    }

    public static function StatefullForm_ChangeState($params, $content) {
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
        if (!array_key_exists('target', $params)) {
            throw new Exception('Не задан параметр target, не знаю, в какое состояние перевести элемент');
        }
        /**
         * Сформируем длинную ссылку
         * @var $bean CStatefullFormBean
         */
        $id = null;
        if (array_key_exists('object', $params)) {
            $object = $params['object'];
            if (is_a($params['object'], 'CModel')) {
                $id = $object->getId();
            } else {
                $id = $object;
            }
        }
        $bean = $params['bean'];
        $address = '';
        if (array_key_exists('address', $params)) {
            $address = $params['address'];
        }
        $ref = $address.'?action=sendEvent'.
            '&event=changeState'.
            '&element='.$params['element'].
            '&formBeanId='.$bean->getBeanId().
            '&target='.$params['target'];
        if (!is_null($id)) {
            $ref .= '&id='.$id;
        }

        $link = '<a href="'.$ref.'">'.$content.'</a>';

        return $link;
    }



    public static function StatefullForm_SendEventLink($params, $content) {
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
        if (!array_key_exists('event', $params)) {
            throw new Exception('Не задан параметр event, не знаю, какое событие отправлять');
        }
        /**
         * Сформируем длинную ссылку
         * @var $bean CStatefullFormBean
         */
        $id = null;
        if (array_key_exists('object', $params)) {
            $object = $params['object'];
            if (is_a($params['object'], 'CModel')) {
                $id = $object->getId();
            } else {
                $id = $object;
            }
        }
        $bean = $params['bean'];
        $address = '';
        if (array_key_exists('address', $params)) {
            $address = $params['address'];
        }
        $ref = $address.'?action=sendEvent'.
                '&event='.$params['event'].
                '&element='.$params['element'].
                '&formBeanId='.$bean->getBeanId();
        if (!is_null($id)) {
            $ref .= '&id='.$id;
        }

        $link = '<a href="'.$ref.'">'.$content.'</a>';

        return $link;
    }
}