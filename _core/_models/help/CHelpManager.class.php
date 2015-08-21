<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 06.10.12
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */
class CHelpManager {
    private static $_cacheHelp = null;
    private static $_recordSet = null;
    /**
     * @static
     * @return CArrayList
     */
    private static function getCacheHelps() {
        if (is_null(self::$_cacheHelp)) {
            self::$_cacheHelp = new CArrayList();
        }
        return self::$_cacheHelp;
    }
    /**
     * Страница справочной системы
     *
     * @static
     * @param $key
     * @return CHelp
     */
    public static function getHelp($key) {
        if (!self::getCacheHelps()->hasElement($key)) {
            $help = null;
            if (is_numeric($key)) {
                $item = CActiveRecordProvider::getById(TABLE_HELP, $key);
                if (!is_null($item)) {
                    $help = new CHelp($item);
                }
            } elseif (is_string($key)) {
                /**
                 * Это поиск справки по названию или адресу страницы. Сделаем допущение, что
                 * в названии страницы обычно не упоминается ?, & и .php
                 */
                if (strpos($key, "?") !== false ||
                    strpos($key, "&") !== false ||
                    strpos($key, ".php") !== false) {
                    // это адрес страницы
                    // сначала попробуем найти по полному совпадению
                    foreach (CActiveRecordProvider::getWithCondition(TABLE_HELP, "url = '".$key."'")->getItems() as $item) {
                        $help = new CHelp($item);
                    }
                    // если в строке запроса есть параметры, то попробуем по одному их отрубать
                    if (is_null($help)) {
                        while (strpos($key, "&") !== false) {
                            $key = substr($key, 0, strpos($key, "&"));
                            foreach (CActiveRecordProvider::getWithCondition(TABLE_HELP, "url = '".$key."'")->getItems() as $item) {
                                $help = new CHelp($item);
                            }
                            // если что-то нашли, то выходим из цикла убирания параметров
                            if (!is_null($help)) {
                                break;
                            }
                        } 
                        if (is_null($help)) {
                        	if (strpos($key, "php?action") === false || 
                        		strpos($key, "/?action") === false || 
                        		strpos($key, "php?action=index") !== false) {
                        			$key = substr($key, 0, strpos($key, "?"));
                        			foreach (CActiveRecordProvider::getWithCondition(TABLE_HELP, "url = '".$key."'")->getItems() as $item) {
                        				$help = new CHelp($item);
                        		}
                        	}
                        }
                        if (is_null($help)) {
                        	foreach (CActiveRecordProvider::getWithCondition(TABLE_HELP, "url = '".$key."index.php'")->getItems() as $item) {
                        		$help = new CHelp($item);
                        	}
                        }
                    }
				} elseif (strpos($key, ".php") === false) {
					foreach (CActiveRecordProvider::getWithCondition(TABLE_HELP, "url = '".$key."'")->getItems() as $item) {
						$help = new CHelp($item);
					}
					if (is_null($help)) {
						foreach (CActiveRecordProvider::getWithCondition(TABLE_HELP, "url = '".$key."index.php'")->getItems() as $item) {
							$help = new CHelp($item);
						}
					}
            	} else {
                    // это русское название куска справки
                    if (is_null($help)) {
                        foreach (CActiveRecordProvider::getWithCondition(TABLE_HELP, "title = '".$key."'")->getItems() as $item) {
                            $help = new CHelp($item);
                        }
                    }
                }
            }
            if (!is_null($help)) {
                self::getCacheHelps()->add($key, $help);
                self::getCacheHelps()->add($help->getId(), $help);
                self::getCacheHelps()->add($help->url, $help);
                self::getCacheHelps()->add($help->title, $help);
            }
        }
        return self::getCacheHelps()->getItem($key);
    }
    /**
     * Справка для текущей страницы
     *
     * @static
     * @return CHelp
     */
    public static function getHelpForCurrentPage() {
        $uri = null;
        if (array_key_exists("REQUEST_URI", $_SERVER)) {
            $uri = $_SERVER["REQUEST_URI"];
            $uri = str_replace(ROOT_FOLDER, "", $uri);
        }
        return self::getHelp($uri);
    }
}
