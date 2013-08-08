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
        return WEB_ROOT."p_lecturers.php";
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
            "/.*\.rar|.*\.zip/i",
            "/.*\.doc.?|.*\.rtf/i",
            "/.*\.xls.?/i",
            "/.*\.pdf/i",
            "/.*\.txt/i",
            "/.*\.exe/i",
            "/.*\.htm|.*\.html|.*\.mht/i",
            "/.*\.jpg$|.*\.jpeg$|.*\.tif$|.*\.bmp$|.*\.png$/i",
            "/.*\.ppt|.*\.pps/i",
            "/.*\.chm|.*\.hlp/i",
            "/.*\.msi|.*\.msp/i"
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
            "install_file.gif"
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
        if ($number % 10 == 1) {
            return $one;
        } elseif ($number >= 2 and $number <= 4){
            return $two;
        } elseif ($number % 10 >= 2 and $number % 10 <= 4) {
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
        foreach ($arr as $path) {
            if ($currentPath == CORE_DS) {
                $currentPath .= $path;
            } else {
                $currentPath .= CORE_DS.$path;
            }
            if (!file_exists($currentPath)) {
                mkdir($currentPath);
            }
        }
    }
}