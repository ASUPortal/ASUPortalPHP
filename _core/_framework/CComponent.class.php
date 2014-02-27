<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.02.14
 * Time: 21:50
 *
 * Вершина иерархии блока расширений фреймворка
 */

class CComponent {

    private function init()
    {

    }

    public function __construct($properties = array())
    {
        // раскладываем все пришедшие параметры в свойства объекта
        foreach ($properties as $key=>$value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            } else {
                throw new Exception("У объекта ".get_class($this)." нет свойства ".$key);
            }
        }
        // запускаем инициализацию, если она нужна
        $this->init();
    }
    public function __call($name, $parameters) {
        throw new Exception("У объекта ".get_class($this)." нет метода ".$name);
    }
}