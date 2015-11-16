<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 15.11.15
 * Time: 20:53
 */

class CStatefullFormWidgets {
    /**
     * Вывод виджета "Поле ввода"
     *
     * @param array $params
     * @return string
     * @throws Exception
     */
    public static function input($params = array()) {
        $widget = new CStatefullFormWidget_Input($params);
        return $widget->run();
    }

    /**
     * Виджет "Скрытое поле"
     *
     * @param array $params
     * @return string
     */
    public static function hidden($params = array()) {
        $widget = new CStatefullFormWidget_Hidden($params);
        return $widget->run();
    }

    /**
     * Виджет "Выбор из списка"
     *
     * @param array $params
     * @return string
     */
    public static function select($params = array()) {
        $widget = new CStatefullFormWidget_Select($params);
        return $widget->run();
    }

    /**
     * Виджет "Кнопка сохранения"
     *
     * @param array $params
     * @return string
     */
    public static function submit($params = array()) {
        $widget = new CStatefullFormWidget_Submit($params);
        return $widget->run();
    }

    /**
     * Виджет для вывода текста с форматированием
     *
     * @param array $params
     * @return string
     */
    public static function text($params = array()) {
        $widget = new CStatefullFormWidget_Text($params);
        return $widget->run();
    }
}