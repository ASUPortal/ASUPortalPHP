<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 06.05.12
 * Time: 9:49
 * To change this template use File | Settings | File Templates.
 *
 * Класс для работы со строкой запроса
 */
class CRequest {
    /**
     * Берет переменную типа int из строки запроса
     *
     * @static
     * @param $key
     * @return int
     */
    public static function getInt($key, $model = null) {
        if (array_key_exists($key, $_GET)) {
           return (int) $_GET[$key];
        } elseif (array_key_exists($key, $_POST)) {
            return (int) $_POST[$key];
        } elseif (array_key_exists("amp;".$key, $_GET)) {
            return (int) $_GET["amp;".$key];
        } elseif (array_key_exists("amp;".$key, $_POST)) {
            return (int) $_POST["amp;".$key];
        } else {
            return 0;
        }
    }
    /**
     * Берет переменную строкового типа из строки запроса
     *
     * @static
     * @param $key
     * @return string
     */
    public static function getString($key, $model = null) {
        if (!is_null($model)) {
            if (array_key_exists($model, $_GET)) {
                $get = $_GET[$model];
                if (array_key_exists($key, $get)) {
                    return $get[$key];
                } else {
                    return "";
                }
            } elseif (array_key_exists($model, $_POST)) {
                $post = $_POST[$model];
                if (array_key_exists($key, $post)) {
                    return $post[$key];
                } else {
                    return "";
                }
            } else {
                return "";
            }
        } else {
            if (array_key_exists($key, $_GET)) {
                return (string) $_GET[$key];
            } elseif (array_key_exists($key, $_POST)) {
                return (string) $_POST[$key];
            } else {
                return "";
            }
        }
    }
    /**
     * Массив из запроса
     *
     * @static
     * @param $key
     * @return array
     */
    public static function getArray($key) {
        if (array_key_exists($key, $_GET)) {
            return $_GET[$key];
        } elseif (array_key_exists($key, $_POST)) {
            return $_POST[$key];
        } else {
            return array();
        }
    }

    /**
     * Параметры глобального фильтра
     *
     * @return array
     */
    public static function getGlobalFilter() {
        $result = array(
            "field" => false,
            "value" => false
        );
        if (CRequest::getString("filter") != "") {
            $filter = CUtils::strRight(CRequest::getString("filter"), "=");
            $params = explode(":", $filter);
            $result["field"] = $params[0];
            $result["value"] = $params[1];
        }
        return $result;
    }

    /**
     * Параметры глобальной сортировки
     *
     * @return array
     */
    public static function getGlobalOrder() {
        $result = array(
            "field" => false,
            "direction" => false
        );
        if (CRequest::getString("order") != "") {
            $result["field"] = CRequest::getString("order");
            $result["direction"] = "asc";
        }
        if (CRequest::getString("direction") != "") {
            $result["direction"] = CRequest::getString("direction");
        }
        return $result;
    }

    /**
     * Класс, для которого выполняется поиск
     *
     * @return string
     */
    public static function getGlobalFilterClass() {
        return CRequest::getString("filterClass");
    }

    /**
     * Значения глобальных переменных запроса
     *
     * @return CArrayList
     */
    public static function getGlobalRequestVariables() {
        $result = new CArrayList();
        foreach ($_GET as $key=>$value) {
            $result->add($key, $value);
        }
        foreach ($_POST as $key=>$value) {
            $result->add($key, $value);
        }
        return $result;
    }

    /**
     * Получить значение фильтра
     *
     * @param $name
     * @return null|int
     */
    public static function getFilter($name) {
        $filters = new CArrayList();
        foreach (explode("_", CRequest::getString("filter")) as $filter) {
            $values = explode(":", $filter);
            if (count($values) > 1) {
                if ($values[1] != 0) {
                    $filters->add($values[0], $values[1]);
                }
            }
        }
        return $filters->getItem($name);
    }
}
