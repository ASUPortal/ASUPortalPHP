<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 06.06.12
 * Time: 0:00
 * To change this template use File | Settings | File Templates.
 */
class CUtils {
    private static $_currentYear = null;
    private static $_currentYearPart = null;
    /**
     * Название дня недели
     *
     * @static
     * @param $date
     * @return string
     */
    public static function getDayOfWeekName($date) {
        $day = date("w", $date);
        switch ($day) {
            case "0": return "Воскресенье";
            case "1": return "Понедельник";
            case "2": return "Вторник";
            case "3": return "Среда";
            case "4": return "Четверг";
            case "5": return "Пятница";
            case "6": return "Суббота";
        }
    }
    /**
     * Номер текущей недели
     * В срочнейшем порядке переписать эту функцию!!!!
     *
     * @static
     * @return float
     */
    public static function getStudyWeekNumber() {
        // эта херня перенесена из date_time.php
        $DateTimeShift=mktime(date("H"), date("i"), date("s"), date("m")  , date("d"), date("Y"));	//сдвиг времени с учетом часовго пояса
        //$dayBegin=mktime(0,0,0,9,3,2012);
        $dayBegin = CSettingsManager::getSettingValue("dayBegin");
        $daysBeetween=floor(($DateTimeShift-$dayBegin)/(60*60*24));     //число прошедших дней
        $weeksBeetween=floor($daysBeetween/7)+1;
        return $weeksBeetween;
    }
    public static function getLogoutLink() {
        return WEB_ROOT."p_administration.php?exit=1";
    }
    public static function getLoginLink() {
        return WEB_ROOT."p_administration.php";
    }
    public static function getLecturersLink() {
        return WEB_ROOT."_modules/_lecturers/index.php";
    }
    /**
     * Текущий год
     *
     * @static
     * @return CTerm
     */
    public static function getCurrentYear() {
        if (is_null(self::$_currentYear)) {
            /**
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_SETTINGS)->getItems() as $setting) {
                self::$_currentYear = CTaxonomyManager::getYear($setting->getItemValue("year_id"));
            }
             */
            self::$_currentYear = CTaxonomyManager::getYear(CSettingsManager::getSettingValue("current_year"));
        }
        return self::$_currentYear;
    }

    /**
     * Текущий семестр
     *
     * @return CTerm|null
     */
    public static function getCurrentYearPart() {
        if (is_null(self::$_currentYearPart)) {
            self::$_currentYearPart = CTaxonomyManager::getYearPart(CSettingsManager::getSettingValue("current_year_part"));
        }
        return self::$_currentYearPart;
    }
    /**
     * Ссылка на текущую страницу в полной версии
     *
     * @static
     * @return string
     */
    public static function getNoWapLink() {
        if (count($_GET) > 0) {
            if (!array_key_exists("nowap", $_GET)) {
                return $_SERVER["REQUEST_URI"]."&nowap";
            }
        } else {
            return $_SERVER["REQUEST_URI"]."?nowap";
        }
    }

    /**
     * Транслитерация введенной строки
     *
     * @param $st
     * @return string
     */
    public static function toTranslit($string) {
        $replace=array(
            "'"=>"",
            "`"=>"",
            "а"=>"a","А"=>"a",
            "б"=>"b","Б"=>"b",
            "в"=>"v","В"=>"v",
            "г"=>"g","Г"=>"g",
            "д"=>"d","Д"=>"d",
            "е"=>"e","Е"=>"e",
            "ж"=>"zh","Ж"=>"zh",
            "з"=>"z","З"=>"z",
            "и"=>"i","И"=>"i",
            "й"=>"y","Й"=>"y",
            "к"=>"k","К"=>"k",
            "л"=>"l","Л"=>"l",
            "м"=>"m","М"=>"m",
            "н"=>"n","Н"=>"n",
            "о"=>"o","О"=>"o",
            "п"=>"p","П"=>"p",
            "р"=>"r","Р"=>"r",
            "с"=>"s","С"=>"s",
            "т"=>"t","Т"=>"t",
            "у"=>"u","У"=>"u",
            "ф"=>"f","Ф"=>"f",
            "х"=>"h","Х"=>"h",
            "ц"=>"c","Ц"=>"c",
            "ч"=>"ch","Ч"=>"ch",
            "ш"=>"sh","Ш"=>"sh",
            "щ"=>"sch","Щ"=>"sch",
            "ъ"=>"","Ъ"=>"",
            "ы"=>"y","Ы"=>"y",
            "ь"=>"","Ь"=>"",
            "э"=>"e","Э"=>"e",
            "ю"=>"yu","Ю"=>"yu",
            "я"=>"ya","Я"=>"ya",
            "і"=>"i","І"=>"i",
            "ї"=>"yi","Ї"=>"yi",
            "є"=>"e","Є"=>"e",
            " " => "_", "(" => "_",
            ")" => "_"
        );
        return (iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace)));
    }

    /**
     * Получить mime-значек для указанного файла
     * @todo переписать функцию при первой возможности, взята из стандартного портал
     *
     * @param $fileName
     * @return string
     */
    public static function getFileMimeIcon($fileName) {
        $str="";
        $out_str="";
        $def_val='other_file.gif';
        $img_path= WEB_ROOT.'images/design/file_types/';
        //echo $str;

        $patterns=array(
            "/.*\.rar|.*\.zip|.*\.7z/i",
            "/.*\.doc.?|.*\.rtf/i",
            "/.*\.xls.?/i",
            "/.*\.pdf/i",
            "/.*\.txt/i",
            "/.*\.exe/i",
            "/.*\.htm|.*\.html|.*\.mht/i",
            "/.*\.jpg$|.*\.jpeg$|.*\.tif$|.*\.bmp$|.*\.png$/i",
            "/.*\.ppt.?|.*\.pps/i",
            "/.*\.chm|.*\.hlp/i",
            "/.*\.msi|.*\.msp/i",
            "/.*\.odt/i"
        );

        $replacements=array(
            "winrar_file.gif",
            "word_file.gif",
            "excel_file.gif",
            "pdf_file.gif",
            "txt_file.gif",
            "app_file.gif",
            "web_file.gif",
            "img_file.gif",
            "ppt_file.gif",
            "help_file.gif",
            "install_file.gif",
            "odt_file.gif"
        );

        $str=preg_replace($patterns, $replacements, $fileName);
        if ($str==$fileName) {
            $out_str.=$img_path.$def_val;
        } else {
            $out_str.=$img_path.$str;
        }
        return $out_str;
    }

    /**
     * Возвращает словоформу для нужного числительного
     *
     * @param $number
     * @param $one
     * @param $two
     * @param $many
     * @return mixed
     */
    public static function getNumberInCase($number, $one, $two, $many) {
        if ($number % 10 == 1 and $number != 11) {
            return $one;
        } elseif ($number >= 2 and $number <= 4) {
            return $two;
        } elseif ($number % 10 >= 2 and $number % 10 <= 4) {
            return $many;
        } elseif ($number == 11) {
            return $many;
        } else {
            return $many;
        }
    }

    /**
     * Представляем дату в формате дд ммммм гггг
     *
     * @param $date
     * @return string
     */
    public static function getDateAsWord($date) {
        $stamp = strtotime($date);
        $res = date("d", $stamp);
        $months = array(
            "01" => "января",
            "02" => "февраля",
            "03" => "марта",
            "04" => "апреля",
            "05" => "мая",
            "06" => "июня",
            "07" => "июля",
            "08" => "августа",
            "09" => "сентября",
            "10" => "октября",
            "11" => "ноября",
            "12" => "декабря"
        );
        $res .= " ".$months[date("m", $stamp)];
        $res .= " ".date("Y", $stamp);
        return $res;
    }

    /**
     * Вернуть название месяца по номеру
     *
     * @param $monthNum
     * @return mixed
     */
    public static function getMonthAsWord($monthNum) {
        $monthNum = str_pad($monthNum, 2, "0");
        $months = array(
            "01" => "января",
            "02" => "февраля",
            "03" => "марта",
            "04" => "апреля",
            "05" => "мая",
            "06" => "июня",
            "07" => "июля",
            "08" => "августа",
            "09" => "сентября",
            "10" => "октября",
            "11" => "ноября",
            "12" => "декабря"
        );
        return $months[$monthNum];
    }

    /**
     * Отправка сообщений phpMailer-ом
     *
     * @param $toEmail
     * @param $messageTitle
     * @param $messageBody
     */
    public static function sendEmail($toEmail, $messageTitle, $messageBody) {
        $mailer = new PHPMailer();
        $mailer->SetFrom(MAIL_SMTP_FROM);
        $mailer->Subject($messageTitle);
        $mailer->MsgHTML($messageBody);
        $mailer->AddAddress($toEmail);
        if (MAIL_SMTP_ENABLED) {
            $mailer->IsSMTP();
            $mailer->Host = MAIL_SMTP_HOST;
            $mailer->SMTPAuth = MAIL_SMTP_AUTH;
            $mailer->Username = MAIL_SMTP_USER;
            $mailer->Password = MAIL_SMTP_PASS;
        }
        $mailer->Send();
    }

    /**
     * @param $string
     * @param $delimiter
     * @return string
     */
    public static function strRight($string, $delimiter) {
        $arr = explode($delimiter, $string);
        if (count($arr) == 1) {
            return $arr[0];
        } else {
            unset($arr[0]);
            return implode($delimiter, $arr);
        }
    }

    /**
     * @param $string
     * @param $delimiter
     * @return mixed
     */
    public static function strLeft($string, $delimiter) {
        $arr = explode($delimiter, $string);
        return $arr[0];
    }

    /**
     * @param $string
     * @param $delimiter
     * @return string
     */
    public static function strRightBack($string, $delimiter) {
        $arr = explode($delimiter, $string);
        return $arr[count($arr) - 1];
    }

    /**
     * @param $string
     * @param $delimiter
     * @return string
     */
    public static function strLeftBack($string, $delimiter) {
        $arr = explode($delimiter, $string);
        if (count($arr) == 1) {
            return $arr[0];
        } else {
            unset($arr[count($arr) - 1]);
            return implode($delimiter, $arr);
        }
    }

    /**
     * Создаем папки в иерархии до указанной директории
     *
     * @param $path
     */
    public static function createFoldersToPath($path) {
        $arr = explode(CORE_DS, $path);
        $currentPath = CORE_DS;
        if (PHP_OS == "WINNT") {
            $currentPath = $arr[0];
        }
        foreach ($arr as $path) {
            if ($currentPath == CORE_DS) {
                $currentPath .= $path;
            } elseif ($currentPath == $path)  {
                $currentPath .= CORE_DS;
            } else {
                // if (PHP_OS == "WINNT") {
                    $currentPath .= CORE_DS;
                // }
                $currentPath .= $path;
            }
            if (!file_exists($currentPath)) {
                mkdir($currentPath);
            }
        }
    }

    /**
     * Проверяет, является ли указанный файл изображением
     *
     * @param $filepath
     * @return bool
     */
    public static function isImage($filepath) {
        // не придумал ничего лучше и проще
        return (@getimagesize($filepath) !== false);
    }
    /**
     * Проверяем, является ли текущий пользователь ботом
     *
     * @return bool
     */
    public static function isHTTPRefererIsBot() {
        $spiders = array(
            "abot",
            "dbot",
            "ebot",
            "hbot",
            "kbot",
            "lbot",
            "mbot",
            "nbot",
            "obot",
            "pbot",
            "rbot",
            "sbot",
            "tbot",
            "vbot",
            "ybot",
            "zbot",
            "bot.",
            "bot/",
            "_bot",
            ".bot",
            "/bot",
            "-bot",
            ":bot",
            "(bot",
            "crawl",
            "slurp",
            "spider",
            "seek",
            "accoona",
            "acoon",
            "adressendeutschland",
            "ah-ha.com",
            "ahoy",
            "altavista",
            "ananzi",
            "anthill",
            "appie",
            "arachnophilia",
            "arale",
            "araneo",
            "aranha",
            "architext",
            "aretha",
            "arks",
            "asterias",
            "atlocal",
            "atn",
            "atomz",
            "augurfind",
            "backrub",
            "bannana_bot",
            "baypup",
            "bdfetch",
            "big brother",
            "biglotron",
            "bjaaland",
            "blackwidow",
            "blaiz",
            "blog",
            "blo.",
            "bloodhound",
            "boitho",
            "booch",
            "bradley",
            "butterfly",
            "calif",
            "cassandra",
            "ccubee",
            "cfetch",
            "charlotte",
            "churl",
            "cienciaficcion",
            "cmc",
            "collective",
            "comagent",
            "combine",
            "computingsite",
            "csci",
            "curl",
            "cusco",
            "daumoa",
            "deepindex",
            "delorie",
            "depspid",
            "deweb",
            "die blinde kuh",
            "digger",
            "ditto",
            "dmoz",
            "docomo",
            "download express",
            "dtaagent",
            "dwcp",
            "ebiness",
            "ebingbong",
            "e-collector",
            "ejupiter",
            "emacs-w3 search engine",
            "esther",
            "evliya celebi",
            "ezresult",
            "falcon",
            "felix ide",
            "ferret",
            "fetchrover",
            "fido",
            "findlinks",
            "fireball",
            "fish search",
            "fouineur",
            "funnelweb",
            "gazz",
            "gcreep",
            "genieknows",
            "getterroboplus",
            "geturl",
            "glx",
            "goforit",
            "golem",
            "grabber",
            "grapnel",
            "gralon",
            "griffon",
            "gromit",
            "grub",
            "gulliver",
            "hamahakki",
            "harvest",
            "havindex",
            "helix",
            "heritrix",
            "hku www octopus",
            "homerweb",
            "htdig",
            "html index",
            "html_analyzer",
            "htmlgobble",
            "hubater",
            "hyper-decontextualizer",
            "ia_archiver",
            "ibm_planetwide",
            "ichiro",
            "iconsurf",
            "iltrovatore",
            "image.kapsi.net",
            "imagelock",
            "incywincy",
            "indexer",
            "infobee",
            "informant",
            "ingrid",
            "inktomisearch.com",
            "inspector web",
            "intelliagent",
            "internet shinchakubin",
            "ip3000",
            "iron33",
            "israeli-search",
            "ivia",
            "jack",
            "jakarta",
            "javabee",
            "jetbot",
            "jumpstation",
            "katipo",
            "kdd-explorer",
            "kilroy",
            "knowledge",
            "kototoi",
            "kretrieve",
            "labelgrabber",
            "lachesis",
            "larbin",
            "legs",
            "libwww",
            "linkalarm",
            "link validator",
            "linkscan",
            "lockon",
            "lwp",
            "lycos",
            "magpie",
            "mantraagent",
            "mapoftheinternet",
            "marvin/",
            "mattie",
            "mediafox",
            "mediapartners",
            "mercator",
            "merzscope",
            "microsoft url control",
            "minirank",
            "miva",
            "mj12",
            "mnogosearch",
            "moget",
            "monster",
            "moose",
            "motor",
            "multitext",
            "muncher",
            "muscatferret",
            "mwd.search",
            "myweb",
            "najdi",
            "nameprotect",
            "nationaldirectory",
            "nazilla",
            "ncsa beta",
            "nec-meshexplorer",
            "nederland.zoek",
            "netcarta webmap engine",
            "netmechanic",
            "netresearchserver",
            "netscoop",
            "newscan-online",
            "nhse",
            "nokia6682/",
            "nomad",
            "noyona",
            "nutch",
            "nzexplorer",
            "objectssearch",
            "occam",
            "omni",
            "open text",
            "openfind",
            "openintelligencedata",
            "orb search",
            "osis-project",
            "pack rat",
            "pageboy",
            "pagebull",
            "page_verifier",
            "panscient",
            "parasite",
            "partnersite",
            "patric",
            "pear.",
            "pegasus",
            "peregrinator",
            "pgp key agent",
            "phantom",
            "phpdig",
            "picosearch",
            "piltdownman",
            "pimptrain",
            "pinpoint",
            "pioneer",
            "piranha",
            "plumtreewebaccessor",
            "pogodak",
            "poirot",
            "pompos",
            "poppelsdorf",
            "poppi",
            "popular iconoclast",
            "psycheclone",
            "publisher",
            "python",
            "rambler",
            "raven search",
            "roach",
            "road runner",
            "roadhouse",
            "robbie",
            "robofox",
            "robozilla",
            "rules",
            "salty",
            "sbider",
            "scooter",
            "scoutjet",
            "scrubby",
            "search.",
            "searchprocess",
            "semanticdiscovery",
            "senrigan",
            "sg-scout",
            "shai'hulud",
            "shark",
            "shopwiki",
            "sidewinder",
            "sift",
            "silk",
            "simmany",
            "site searcher",
            "site valet",
            "sitetech-rover",
            "skymob.com",
            "sleek",
            "smartwit",
            "sna-",
            "snappy",
            "snooper",
            "sohu",
            "speedfind",
            "sphere",
            "sphider",
            "spinner",
            "spyder",
            "steeler/",
            "suke",
            "suntek",
            "supersnooper",
            "surfnomore",
            "sven",
            "sygol",
            "szukacz",
            "tach black widow",
            "tarantula",
            "templeton",
            "/teoma",
            "t-h-u-n-d-e-r-s-t-o-n-e",
            "theophrastus",
            "titan",
            "titin",
            "tkwww",
            "toutatis",
            "t-rex",
            "tutorgig",
            "twiceler",
            "twisted",
            "ucsd",
            "udmsearch",
            "url check",
            "updated",
            "vagabondo",
            "valkyrie",
            "verticrawl",
            "victoria",
            "vision-search",
            "volcano",
            "voyager/",
            "voyager-hc",
            "w3c_validator",
            "w3m2",
            "w3mir",
            "walker",
            "wallpaper",
            "wanderer",
            "wauuu",
            "wavefire",
            "web core",
            "web hopper",
            "web wombat",
            "webbandit",
            "webcatcher",
            "webcopy",
            "webfoot",
            "weblayers",
            "weblinker",
            "weblog monitor",
            "webmirror",
            "webmonkey",
            "webquest",
            "webreaper",
            "websitepulse",
            "websnarf",
            "webstolperer",
            "webvac",
            "webwalk",
            "webwatch",
            "webwombat",
            "webzinger",
            "wget",
            "whizbang",
            "whowhere",
            "wild ferret",
            "worldlight",
            "wwwc",
            "wwwster",
            "xenu",
            "xget",
            "xift",
            "xirq",
            "yandex",
            "yanga",
            "yeti",
            "yodao",
            "zao/",
            "zippp",
            "zyborg",
            "...."
        );
        foreach($spiders as $spider) {
            if ( stripos($_SERVER['HTTP_USER_AGENT'], $spider) !== false ) return true;
        }
        return false;
    }

    /**
     * Mime-type файла по указанному пути
     *
     * @param $filename
     * @return mixed
     */
    public static function getMimetype($filename) {
        $filetype = "";
        $mime_types = array(
        
        		'txt' => 'text/plain',
        		'htm' => 'text/html',
        		'html' => 'text/html',
        		'php' => 'text/html',
        		'css' => 'text/css',
        		'js' => 'text/x-javascript',
        		'json' => 'application/json',
        		'xml' => 'text/xml',
        		'swf' => 'application/x-flash-video',
        		'flv' => 'video/x-flv',
        
        		// images
        		'png' => 'image/png',
        		'jpe' => 'image/jpeg',
        		'jpeg' => 'image/jpeg',
        		'jpg' => 'image/jpeg',
        		'gif' => 'image/gif',
        		'bmp' => 'image/bmp',
        		'ico' => 'image/x-ico',
        		'tiff' => 'image/tiff',
        		'tif' => 'image/tiff',
        		'svg' => 'image/svg+xml',
        		'svgz' => 'image/svg+xml',
        
        		// archives
        		'zip' => 'application/x-zip',
        		'7z' => 'application/x-7zip',
        		'tar' => 'application/x-tar',
        		'rar' => 'application/x-rar',
        		'exe' => 'application/x-executable',
        		'msi' => 'application/x-msdownload',
        		'cab' => 'application/vnd.ms-cab-compressed',
        
        		// audio/video
        		'mp3' => 'audio/x-mpeg',
        		'qt' => 'video/quicktime',
        		'mov' => 'video/quicktime',
        
        		// adobe
        		'pdf' => 'application/pdf',
        		'psd' => 'image/vnd.adobe.photoshop',
        		'ai' => 'application/postscript',
        		'eps' => 'application/postscript',
        		'ps' => 'application/postscript',
        
        		// ms office
        		'doc' => 'application/msword',
        		'rtf' => 'text/richtext',
        		'xls' => 'application/vnd.ms-excel',
        		'ppt' => 'application/vnd.ms-powerpoint',
        
        		// ms office new
        		'docx' => 'application/msword',
        		'xlsx' => 'application/vnd.ms-excel',
        		'pptx' => 'application/vnd.ms-powerpoint',
        
        		// open office
        		'odt' => 'x-office-document',
        		'ods' => 'x-office-spreadsheet',
        		'odp' => 'x-office-presentation',
        		'odg' => 'x-office-drawing',
        );
        $ext = strtolower(array_pop(explode('.', $filename)));
        if (array_key_exists($ext, $mime_types)) {
        	$filetype = $mime_types[$ext];
        	$filetype = str_replace("/", "-", $filetype);
        } else {
        	$filetype = "unknown";
        }
        return $filetype;
    }
	public static function getTextStringInCorrectEncoding($text) {
		if (is_null(CSession::getCurrentController())) {
			return mb_convert_encoding($text, "Windows-1251");
		} else {
			return $text;
		}
	}
	/**
	 * Замена при выводе сообщений на экран для форматирования
	 *
	 * @param $s
	 * @return string
	 */
	public static function getReplacedMessage($s) {
		
		$s=str_replace ("\r\n","<br>",$s);
		 
		$s=str_replace ("[url]","<a href='http://",$s);
		$s=str_replace ("[/url]","' target='_blank' style='font-weight:normal; text-decoration:underline;'>Ресурс</a>",$s);
		 
		$s=str_replace ("[quote]","<u>Цитата</u><br><span style='color:grey;background:white;'>",$s);
		$s=str_replace ("[/quote]","</span><br>",$s);
		 
		$s=str_replace ("[b]","<b>",$s);
		$s=str_replace ("[/b]","</b>",$s);
		 
		$s=str_replace ("[u]","<u>",$s);
		$s=str_replace ("[/u]","</u>",$s);
		 
		$s=str_replace ("[i]","<i>",$s);
		$s=str_replace ("[/i]","</i>",$s);
	
		return $s;
	}

    /**
     * Вернуть список классов, реализующих выбранный интерфейс
     *
     * @param $interfaceName
     * @param string $folder
     * @return CArrayList
     */
    public static function getAllClassesWithInterface($interfaceName, $folder = "", $excludes = array()) {
        if ($folder == "") {
            $folder = CORE_CWD.CORE_DS."_core".CORE_DS."_models";
        }
        $result = new CArrayList();
        $folderHandle = opendir($folder);
        while (false !== ($file = readdir($folderHandle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($folder.CORE_DS.$file)) {
                    $part = self::getAllClassesWithInterface($interfaceName, $folder.CORE_DS.$file, $excludes);
                    $result->addAll($part);
                } else {
                    if (mb_strpos($file, ".class.php") !== false && !in_array(CUtils::strLeft($file, '.class.php'), $excludes)) {
                        if (!class_exists($file, false)) {
                            require_once($folder.CORE_DS.$file);
                        }
                        $model = substr($file, 0, strpos($file, "."));
                        if (is_a($model, $interfaceName, true)) {
                            if (!interface_exists($model, false)) {
                                $reflection = new ReflectionClass($model);
                                if ($reflection->isInstantiable()) {
                                    $object = new $model();
                                    $result->add(get_class($object), $object);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
    
    /**
     * Путь к текущему исполняемому скрипту
     *
     * @static
     * @return mixed
     */
    public static function getScriptName() {
    	return $_SERVER["SCRIPT_NAME"];
    }
    
    /**
     * Путь к корневой директории сервера
     *
     * @static
     * @return mixed
     */
    public static function getDocumentRoot() {
    	return $_SERVER["DOCUMENT_ROOT"];
    }
    
    /**
     * Создание изображения из формата BMP
     * 
     * @param string $filename
     * @return resource|boolean
     */
    public static function imageCreateFromBMP($filename) {
    	$tmp_name = tempnam("/tmp", "GD");
    	if(CUtils::convertBMP2GD($filename, $tmp_name)) {
    		$img = imagecreatefromgd($tmp_name);
    		unlink($tmp_name);
    		return $img;
    	} return false;
    }
    
    /**
     * Преобразование изображения формата BMP
     * 
     * @param string $src
     * @param string $dest
     * @return boolean
     */
    public static function convertBMP2GD($src, $dest = false) {
    	if(!($src_f = fopen($src, "rb"))) {
    		return false;
    	}
    	if(!($dest_f = fopen($dest, "wb"))) {
    		return false;
    	}
    	$header = unpack("vtype/Vsize/v2reserved/Voffset", fread($src_f, 14));
    	$info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant", fread($src_f, 40));
    
    	extract($info);
    	extract($header);
    
    	if($type != 0x4D42) { // signature "BM"
    		return false;
    	}
    
    	$palette_size = $offset - 54;
    	$ncolor = $palette_size / 4;
    	$gd_header = "";
    	// true-color vs. palette
    	$gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
    	$gd_header .= pack("n2", $width, $height);
    	$gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
    	if($palette_size) {
    		$gd_header .= pack("n", $ncolor);
    	}
    	// no transparency
    	$gd_header .= "\xFF\xFF\xFF\xFF";
    
    	fwrite($dest_f, $gd_header);
    
    	if($palette_size) {
    		$palette = fread($src_f, $palette_size);
    		$gd_palette = "";
    		$j = 0;
    		while($j < $palette_size) {
    			$b = $palette{$j++};
    			$g = $palette{$j++};
    			$r = $palette{$j++};
    			$a = $palette{$j++};
    			$gd_palette .= "$r$g$b$a";
    		}
    		$gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
    		fwrite($dest_f, $gd_palette);
    	}
    
    	$scan_line_size = (($bits * $width) + 7) >> 3;
    	$scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size &
    			0x03) : 0;
    
    	for($i = 0, $l = $height - 1; $i < $height; $i++, $l--) {
    		// BMP stores scan lines starting from bottom
    		fseek($src_f, $offset + (($scan_line_size + $scan_line_align) *
    				$l));
    		$scan_line = fread($src_f, $scan_line_size);
    		if($bits == 24) {
    			$gd_scan_line = "";
    			$j = 0;
    			while($j < $scan_line_size) {
    				$b = $scan_line{$j++};
    				$g = $scan_line{$j++};
    				$r = $scan_line{$j++};
    				$gd_scan_line .= "\x00$r$g$b";
    			}
    		}
    		else if($bits == 8) {
    			$gd_scan_line = $scan_line;
    		}
    		else if($bits == 4) {
    			$gd_scan_line = "";
    			$j = 0;
    			while($j < $scan_line_size) {
    				$byte = ord($scan_line{$j++});
    				$p1 = chr($byte >> 4);
    				$p2 = chr($byte & 0x0F);
    				$gd_scan_line .= "$p1$p2";
    			} $gd_scan_line = substr($gd_scan_line, 0, $width);
    		}
    		else if($bits == 1) {
    			$gd_scan_line = "";
    			$j = 0;
    			while($j < $scan_line_size) {
    				$byte = ord($scan_line{$j++});
    				$p1 = chr((int) (($byte & 0x80) != 0));
    				$p2 = chr((int) (($byte & 0x40) != 0));
    				$p3 = chr((int) (($byte & 0x20) != 0));
    				$p4 = chr((int) (($byte & 0x10) != 0));
    				$p5 = chr((int) (($byte & 0x08) != 0));
    				$p6 = chr((int) (($byte & 0x04) != 0));
    				$p7 = chr((int) (($byte & 0x02) != 0));
    				$p8 = chr((int) (($byte & 0x01) != 0));
    				$gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
    			} $gd_scan_line = substr($gd_scan_line, 0, $width);
    		}
    
    		fwrite($dest_f, $gd_scan_line);
    	}
    	fclose($src_f);
    	fclose($dest_f);
    	return true;
    }
    
}
