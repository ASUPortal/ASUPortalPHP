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
}