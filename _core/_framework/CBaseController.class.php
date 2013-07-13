<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 06.05.12
 * Time: 9:47
 * To change this template use File | Settings | File Templates.
 *
 * Основной контроллер, от него наследовать все остальные.
 * Алгоритм использования контроллеров.
 */

class CBaseController {
    private $_action = null;
    private $_js = null;
    private $_css = null;
    private $_jsInline = null;
    private $_data = null;
    protected $_smartyEnabled = false;
    private $_smarty = null;
    private $_jqInline = null;
    private $_datePickers = null;
    private $_jsIEOnly = null;
    private $_cssAbs = null;
    protected $_useDojo = false;

    /**
     * Конструктор базового класса. Определяет, какой метод
     * дальше использовать
     */
    public function __construct() {
        if (CRequest::getString("action") == null) {
            $this->_action =  "index";
        } else {
            $this->_action = CRequest::getString("action");
        }

        // подключаем jQuery по умолчанию
        $this->addJSInclude(CSettingsManager::getSettingValue("jquery_path"));
        $this->addJSInclude("_core/jquery.cookie.js");
        $this->addJSInlineInclude("var web_root = '".WEB_ROOT."';");
        // ядреные модули
        $this->addJSInclude("_core/core.js");
        $this->setData("wap_mode", false);
        $this->setData("no_wap_link", CUtils::getNoWapLink());
        // подключение системы уведомлений
        $this->addJSInclude("_modules/_messages/sticky.full.js");
        $this->addCSSInclude("_modules/_messages/sticky.full.css");
        // выключение режима wap
        if (array_key_exists("nowap", $_GET)) {
            if (array_key_exists("wap_mode", $_COOKIE)) {
                if (!setcookie("wap_mode", "false", 1)) {
                    die("Ошибка работы с cookie!");
                };
                unset($_COOKIE["wap_mode"]);
            }
        }
        // включение режима wap
        if (array_key_exists("wap_mode", $_COOKIE)) {
            if ($_COOKIE["wap_mode"] == "true") {
                $this->setData("wap_mode", true);
            }
        }
        // фиксы для IE
        $this->addJSIEOnly(8, "_core/iefix8.js");

        /**
         * Пришло время помощника =)
         * Если у пользователя включена проверка ящика,
         * то подключаем скрипт проверки
         */
        if (!is_null(CSession::getCurrentUser())) {
            if (!is_null(CSession::getCurrentUser()->getPersonalSettings())) {
                $settings = CSession::getCurrentUser()->getPersonalSettings();
                if ($settings->isCheckMessages()) {
                    $this->addJSInclude("_modules/_messages/checker.js");
                }
            }
        }
        /**
         * AJAX для портала
         */
        $this->addJSInclude("_core/jquery.form.js");
        $this->addJSInclude("_core/jquery.ajax.js");

        $action = "action".$this->_action;
        if (method_exists($this, $action)) {
            $this->$action();
        }
    }
    /**
     * Лист подключаемых либ
     *
     * @return CArrayList
     */
    public function getJSIncludes() {
        if (is_null($this->_js)) {
            $this->_js = new CArrayList();
        }
        return $this->_js;
    }
    /**
     * Подключает либу.
     * Либы лежат в папке scripts
     *
     * @param $js
     */
    public function addJSInclude($js) {
        $this->getJSIncludes()->add($js, $js);
    }
    /**
     * Лист подключаемых листов каскадных стилей
     *
     * @return CArrayList
     */
    public function getCSSIncludes() {
        if (is_null($this->_css)) {
            $this->_css = new CArrayList();
        }
        return $this->_css;
    }

    /**
     * @param $css
     * @param $absPath
     */
    public function addCSSInclude($css, $absPath = false) {
        if (!$absPath) {
            $this->getCSSIncludes()->add($css, $css);
        } else {
            $this->getCSSAbsIncludes()->add($css, $css);
        }
    }

    /**
     * @return CArrayList|null
     */
    private function getCSSAbsIncludes() {
        if (is_null($this->_cssAbs)) {
            $this->_cssAbs = new CArrayList();
        }
        return $this->_cssAbs;
    }
    /**
     * Лист подключаемых текстов JS
     *
     * @return CArrayList
     */
    public function getJSInlineIncludes() {
        if (is_null($this->_jsInline)){
            $this->_jsInline = new CArrayList();
        }
        return $this->_jsInline;
    }
    /**
     * Лист всего, что подключается в jQuery.ready()
     *
     * @return CArrayList
     */
    public function getJQInlineIncludes() {
        if (is_null($this->_jqInline)) {
            $this->_jqInline = new CArrayList();
        }
        return $this->_jqInline;
    }
    /**
     * Добавляем код в список подключаемых автоматически в
     * jQuery.ready()
     *
     * @param $code
     */
    public function addJQInlineInclude($code) {
        $this->getJQInlineIncludes()->add($this->getJQInlineIncludes()->getCount(), trim($code));
    }
    /**
     * Добавляет отображаемые в head-е JS код.
     * Код автоматом оборачивается в блок.
     * Все коды внутри одного блока будут
     *
     * @param $code
     */
    public function addJSInlineInclude($code) {
        $this->getJSInlineIncludes()->add($this->getJSInlineIncludes()->getCount(), $code);
    }
    /**
     * Отобразить выбранное представление.
     * Данные передать через setData
     *
     * @param $view
     */
    public function renderView($view) {

        if ($this->_smartyEnabled) {
            foreach ($this->getData()->getItems() as $key=>$value) {
                $this->getSmarty()->assign($key, $value);
            }
            $this->getSmarty()->assign("css", $this->getCSSIncludes()->getItems());
            $this->getSmarty()->assign("js", $this->getJSIncludes()->getItems());
            $this->getSmarty()->assign("jsIe", $this->getJSIEOnly()->getItems());
            $this->getSmarty()->assign("web_root", WEB_ROOT);
            $this->getSmarty()->assign("page_title", $this->getData()->getItem("_page_title"));
            $this->getSmarty()->assign("jsInline", $this->getJSInlineIncludes()->getItems());
            $this->getSmarty()->assign("jqInline", $this->getJQInlineIncludes()->getItems());
            $this->getSmarty()->assign("date_pickers", $this->getDatePickers()->getItems());
            $this->getSmarty()->assign("icon_theme", ICON_THEME);
            $this->getSmarty()->assign("css_abs", $this->getCSSAbsIncludes()->getItems());
            $this->getSmarty()->display($view);
        } else {
            $data = $this->getData();
            extract($this->getData()->getItems());
            require(VIEWS_DIR.$view);
            exit;
        }
    }
    /**
     * Отобразить выбранное представление.
     * Данные передать через setData
     *
     * @param $view
     */
    public function renderViewAndContinue($view) {
        $data = $this->getData();
        extract($this->getData()->getItems());
        require(VIEWS_DIR.$view);
    }
    /**
     * Добавление данные в передаваемые в представление
     *
     * @param $key
     * @param $value
     */
    public function setData($key, $value) {
        $this->getData()->add($key, $value);
    }
    /**
     * Данные, передаваемые в представление
     *
     * @return CArrayList
     */
    public function getData() {
        if (is_null($this->_data)) {
            $this->_data = new CArrayList();
        }
        return $this->_data;
    }
    /**
     * Переадресация на указанный адрес
     *
     * @param $url
     */
    public function redirect($url) {
        if (!headers_sent()) {
            header("location: ".$url);
        } else {
            echo '
                <script>
                    window.location.href="'.$url.'";
                </script>';
        }
    }
    /**
     * Переадресация на страницу "Недостаточно прав"
     */
    public function redirectNoAccess() {
        $this->redirect(NO_ACCESS_URL);
    }
    /**
     * Устанавливает название страницы
     *
     * @param $title
     */
    public function setPageTitle($title) {
        global $pg_title;
        $pg_title = $title;
        $this->getData()->add("_page_title", $title);
    }
    /**
     * @return Smarty
     */
    protected function getSmarty() {
        if (is_null($this->_smarty)) {
            // подключаем Smarty
            $config = CApp::getApp()->getConfig();
            $smartyConfig = $config["smarty"];

            $this->_smarty = new Smarty();
            $this->_smarty->caching = $smartyConfig["cacheEnabled"];
            $this->_smarty->setTemplateDir(SMARTY_TEMPLATES);
            $this->_smarty->setCompileDir(SMARTY_COMPILE);
            $this->_smarty->setCacheDir(SMARTY_CACHE);

            // подключаем очищающий плагинчик
            //$this->_smarty->loadFilter("output", "trimwhitespace");

            // постоянно нужная ерунда типа управлялки меню,
            // нет другого места, откуда ее можно хорошо подцепить
            $this->addCSSInclude("_core/core.css");
            $this->addCSSInclude("_modules/_sdmenu/sdmenu.css");
            $this->addJSInclude("_modules/_sdmenu/sdmenu.js");
            $this->addJQInlineInclude('
                if ($("#mainMenu").length) {
                    myMenu = new SDMenu("mainMenu");
                    myMenu.oneSmOnly = true;
                    myMenu.init();
                }
            ');

        }
        return $this->_smarty;
    }
    /**
     * Устанавливает для таблицы с указанным id фильтрацию.
     *
     * @param $id
     */
    public function setTableFilter($id) {
        $this->addJSInclude("_core/jTableFilter/picnet.table.filter.min.js");
        $this->addJQInlineInclude('$("#'.$id.'").tableFilter();');
    }
    /**
     * Устанавливает для таблицы сортировалку
     *
     * @param $id
     */
    public function setTableSort($id) {
        $this->addJSInclude("_core/jTableSort/jquery.tablesorter.min.js");
        $this->addCSSInclude("_core/jTableSort/style.css");
        $this->addJQInlineInclude('$("#'.$id.'").addClass("tablesorter");');
        $this->addJQInlineInclude('$("#'.$id.'").tablesorter({
            widthFixed: true
        });');
    }
    public function setTablePager($id) {
        $this->addJSInclude("_core/jTablePager/jquery.tablesorter.pager.js");
        // создаем все необходимые элементы управления, чтобы в
        // шаблонах о них не задумываться
        $this->addJQInlineInclude('
            tForm = jQuery("<form/>");
            tImg = jQuery("<img/>", {
                src: "'.WEB_ROOT.'images/tango/16x16/actions/go-first.png",
                class: "first"
            });
            tForm.append(tImg);
            tImg = jQuery("<img/>", {
                src: "'.WEB_ROOT.'images/tango/16x16/actions/go-previous.png",
                class: "prev"
            });
            tForm.append(tImg);
            tInput = jQuery("<input/>", {
                type: "text",
                class: "pagedisplay"
            });
            tForm.append(tInput);
            tImg = jQuery("<img/>", {
                src: "'.WEB_ROOT.'images/tango/16x16/actions/go-next.png",
                class: "next"
            });
            tForm.append(tImg);
            tImg = jQuery("<img/>", {
                src: "'.WEB_ROOT.'images/tango/16x16/actions/go-last.png",
                class: "last"
            });
            tForm.append(tImg);
            tSelect = jQuery("<select/>", {
                class: "pagesize"
            });
            tSelect.append("<option selected=\"selected\"  value=\"10\">10</option>");
            tSelect.append("<option value=\"20\">20</option>");
            tSelect.append("<option value=\"40\">40</option>");
            tForm.append(tSelect);
            tDiv = jQuery("<div/>", {
                id: "pager",
                class: "pager"
            });
            tDiv.append(tForm);
            $("#'.$id.'").parent().append(tDiv);
        ');
        $this->addJQInlineInclude('$("#'.$id.'").tablesorterPager({
            container: $("#pager"),
            positionFixed: false
        });');
    }
    /**
     * Автоматически добавляет к таблице все доступные плюшки вроде поиска,
     * пагинации и сортировки
     *
     * @param $id
     */
    public function extendTable($id) {
        $this->setTableFilter($id);
        $this->setTableSort($id);
        $this->setTablePager($id);
    }
    /**
     * Добавление выбиратора даты к указанному полю
     *
     * @param $field
     */
    public function addDatePicker($field) {
        $this->getDatePickers()->add($field, $field);

        // все равно будет подключено только один раз
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");

        // jQuery обработчик для даты
        $this->addJQInlineInclude('
            $("#'.$field.'").datepicker({
                dateFormat: "dd.mm.yy"
            });
        ');
    }
    /**
     * Список полей, для которых добавлен выбиратор даты
     *
     * @return CArrayList
     */
    private function getDatePickers() {
        if (is_null($this->_datePickers)) {
            $this->_datePickers = new CArrayList();
        }
        return $this->_datePickers;
    }
    /**
     * JS только для InternetExlporer
     * 
     * @return CArrayList
     */
    private function getJSIEOnly() {
    	if (is_null($this->_jsIEOnly)) {
    		$this->_jsIEOnly = new CArrayList();
    	}
    	return $this->_jsIEOnly;
    }
	/**
	 * Скрипт только для указанной версии IE
	 * Костыли, но ИЕ такой как он есть..)
	 * 
	 * @param int $version
	 * @param string $js
	 */
    public function addJSIEOnly($version, $js) {
    	$v = new CArrayList();
    	if ($this->getJSIEOnly()->hasElement($version)) {
    		$v = $this->getJSIEOnly()->getItem($version);
    	}
    	$v->add($js, $js);
    	$this->getJSIEOnly()->add($version, $v);
    }
}
