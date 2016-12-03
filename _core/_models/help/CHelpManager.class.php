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
    /**
     * Модальное окно для страницы локальной Википедии
     *
     * @param CHelp $help
     * @return String
     */
    public static function getWikiAddressModalWindow(CHelp $help) {
        require_once(CORE_CWD."/_core/_external/smarty/vendor/simple_html_dom.php");
        $proxy = CSettingsManager::getSettingValue("proxy_address");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_PROXY, $proxy);
        curl_setopt($curl, CURLOPT_URL, $help->wiki_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
        $str = curl_exec($curl);
        curl_close($curl);
        $html = str_get_html($str);
        if (!empty($html)) {
            if(count($html->find('div[id="mw-content-text"]'))) {
                foreach($html->find('div[id="mw-content-text"]') as $value) {
                    foreach($value->find('img') as $element) {
                        foreach($element->find('a') as $a) {
                            $a->href = CSettingsManager::getSettingValue("wiki_address").$a->href;
                        }
                        $element->src = CSettingsManager::getSettingValue("wiki_address").$element->src;
                    }
                    $modalWindow = CHtml::modalWindow("wikiHelp", "Справка", $value);
                }
            } else {
                $modalWindow = CHtml::modalWindow("wikiHelp", "Справка", "Указанной страницы нет в локальной Википедии кафедры!");
            }
            $html->clear();
            unset($html);
        } else {
            $modalWindow = CHtml::modalWindow("wikiHelp", "Справка", "Локальная Википедия недоступна!");
        }
        return $modalWindow;
    }
}
