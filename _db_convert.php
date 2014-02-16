<?php

require_once("sql_connect_credentials.php");
/**
 * Перекидываем настройки соединения с БД в константы
 */
define("DB_HOST", $sql_host);
define("DB_USER", $sql_login);
define("DB_PASSWORD", $sql_passw);
define("DB_DATABASE", $sql_base);

class DatabaseConverter {
    private $convertions = array(
        "_bd_student" => array(
            "fam" => array("windows-1251" => "utf-8"),
            "im" => array("windows-1251" => "utf-8"),
            "ot" => array("windows-1251" => "utf-8"),
            "spec" => array("windows-1251" => "utf-8"),
        ),
        "backup_settings" => array(
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "biography" => array(
            "main_text" => array("utf-8" => "utf-8"),
        ),
        "courses" => array(
            "place" => array("windows-1251" => "utf-8"),
            "name" => array("windows-1251" => "utf-8"),
            "document" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "develop_news" => array(
            "title" => array("windows-1251" => "utf-8"),
            "text" => array("utf-8" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "develop_news_type" => array(
            "name" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "diploms" => array(
            "dipl_name" => array("windows-1251" => "utf-8"),
            "recenz" => array("windows-1251" => "utf-8"),
            "pract_place" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
            "diplom_number" => array("windows-1251" => "utf-8"),
            "diplom_regnum" => array("windows-1251" => "utf-8"),
        ),
        "disser" => array(
            "order_num_out" => array("windows-1251" => "utf-8"),
            "order_num_begin" => array("windows-1251" => "utf-8"),
            "tema" => array("utf-8" => "utf-8"),
            "disser_type" => array("windows-1251" => "utf-8"),
            "doc_seriya" => array("windows-1251" => "utf-8"),
            "dis_sov_num" => array("windows-1251" => "utf-8"),
            "spec_nom" => array("utf-8" => "utf-8"),
            "comment" => array("utf-8" => "utf-8"),
            "vak_num" => array("windows-1251" => "utf-8"),
            "god_zach" => array("windows-1251" => "utf-8"),
        ),
        "dolgnost" => array(
            "name" => array("windows-1251" => "utf-8"),
            "name_short" => array("windows-1251" => "utf-8"),
        ),
        "files" => array(
            "browserFile" => array("windows-1251" => "utf-8"),
            "add_link" => array("windows-1251" => "utf-8"),
        ),
        "file_types" => array(
            "name" => array("windows-1251" => "utf-8"),
            "name_short" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "holidays" => array(
            "name_hol" => array("windows-1251" => "utf-8"),
        ),
        "hours_kind" => array(
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "hours_kind_type" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "hours_year" => array(
            "bud_commerce" => array("windows-1251" => "utf-8"),
        ),
        "izdan" => array(
            "page_range" => array("windows-1251" => "utf-8"),
            "copy" => array("windows-1251" => "utf-8"),
            "grif" => array("windows-1251" => "utf-8"),
            "volume" => array("windows-1251" => "utf-8"),
            "name" => array("utf-8" => "utf-8"),
            "publisher" => array("utf-8" => "utf-8"),
            "bibliografya" => array("utf-8" => "utf-8"),
            "authors_all" => array("utf-8" => "utf-8"),
        ),
        "izdan_type" => array(
            "name" => array("windows-1251" => "utf-8"),
            "name_short" => array("windows-1251" => "utf-8"),
        ),
        "izmen" => array(
            "izmenenie" => array("windows-1251" => "utf-8"),
        ),
        "kadri" => array(
            "fio" => array("windows-1251" => "utf-8"),
            "fio_short" => array("windows-1251" => "utf-8"),
            "ekspert_spec" => array("windows-1251" => "utf-8"),
            "ekspert_kluch_slova" => array("windows-1251" => "utf-8"),
            "work_place" => array("windows-1251" => "utf-8"),
            "primech" => array("windows-1251" => "utf-8"),
            "passp_place" => array("windows-1251" => "utf-8"),
            "birth_place" => array("windows-1251" => "utf-8"),
            "add_home" => array("windows-1251" => "utf-8"),
            "add_work" => array("windows-1251" => "utf-8"),
            "passp_date" => array("windows-1251" => "utf-8"),
            "nation" => array("windows-1251" => "utf-8"),
            "din_nauch_kar" => array("windows-1251" => "utf-8"),
            "nagradi" => array("windows-1251" => "utf-8"),
            "add_contact" => array("windows-1251" => "utf-8"),
            "nauch_eksper" => array("windows-1251" => "utf-8"),
            "social" => array("windows-1251" => "utf-8"),
            "prepod_rabota" => array("windows-1251" => "utf-8"),
            "tel_home" => array("windows-1251" => "utf-8"),
            "insurance_num" => array("windows-1251" => "utf-8"),
            "site" => array("windows-1251" => "utf-8"),
        ),
        "language" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "levels" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "mails" => array(
            "mail_title" => array("windows-1251" => "utf-8"),
            "mail_text" => array("windows-1251" => "utf-8"),
            "file_name" => array("windows-1251" => "utf-8"),
        ),
        "mails_backup" => array(
            "mail_title" => array("windows-1251" => "utf-8"),
            "mail_text" => array("windows-1251" => "utf-8"),
            "file_name" => array("windows-1251" => "utf-8"),
        ),
        "mantis_user_table" => array(
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "news" => array(
            "title" => array("windows-1251" => "utf-8"),
            "file" => array("utf-8" => "utf-8"),
        ),
        "obrazov" => array(
            "seriya" => array("windows-1251" => "utf-8"),
            "obraz_type" => array("windows-1251" => "utf-8"),
            "zaved_name" => array("utf-8" => "utf-8"),
            "spec_name" => array("utf-8" => "utf-8"),
            "kvalifik" => array("utf-8" => "utf-8"),
            "comment" => array("utf-8" => "utf-8"),
        ),
        "order_type" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "order_type_money" => array(
            "name" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "orders" => array(
            "prev_order" => array("windows-1251" => "utf-8"),
            "main_work_place" => array("windows-1251" => "utf-8"),
            "num_order" => array("windows-1251" => "utf-8"),
            "conditions" => array("windows-1251" => "utf-8"),
        ),
        "orders_dep" => array(
            "title" => array("windows-1251" => "utf-8"),
            "text" => array("utf-8" => "utf-8"),
            "num" => array("windows-1251" => "utf-8"),
            "date" => array("windows-1251" => "utf-8"),
        ),
        "orders_dep_type" => array(
            "name" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "otmetka" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "pchart" => array(
            "zvanie" => array("windows-1251" => "utf-8"),
            "dolzhnost" => array("windows-1251" => "utf-8"),
            "rabota" => array("windows-1251" => "utf-8"),
            "vichet" => array("windows-1251" => "utf-8"),
        ),
        "pg_uploads" => array(
            "title" => array("windows-1251" => "utf-8"),
            "name" => array("windows-1251" => "utf-8"),
            "comment" => array("utf-8" => "utf-8"),
            "page_content" => array("utf-8" => "utf-8"),
        ),
        "pol" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "protocol_details" => array(
            "text_content" => array("utf-8" => "utf-8"),
        ),
        "protocol_nms_details" => array(
            "text_content" => array("utf-8" => "utf-8"),
            "opinion_text" => array("windows-1251" => "utf-8"),
        ),
        "protocol_opinions" => array(
            "name" => array("windows-1251" => "utf-8"),
            "name_short" => array("windows-1251" => "utf-8"),
        ),
        "protocol_visit" => array(
            "matter_text" => array("utf-8" => "utf-8"),
        ),
        "protocols" => array(
            "program_content" => array("utf-8" => "utf-8"),
            "comment" => array("utf-8" => "utf-8"),
        ),
        "protocols_nms" => array(
            "program_content" => array("utf-8" => "utf-8")
        ),
        "settings2" => array(
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "specialities" => array(
            "name" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "specialities_science" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "spr_nauch_met_uch_rab" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "spravochnik_vidov_rabot" => array(
            "name" => array("windows-1251" => "utf-8"),
            "time_norm" => array("windows-1251" => "utf-8"),
        ),
        "spr_vichet" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "sprav_links" => array(
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "sprav_main" => array(
            "name" => array("windows-1251" => "utf-8"),
            "name_short" => array("windows-1251" => "utf-8"),
        ),
        "spravochnik_uch_rab" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "sso_systems" => array(
            "comment" => array("windows-1251" => "utf-8"),
            "response_templ" => array("windows-1251" => "utf-8"),
            "login_pg_path" => array("windows-1251" => "utf-8"),
            "logout_pg_path" => array("windows-1251" => "utf-8"),
        ),
        "stepen" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "students" => array(
            "fio" => array("windows-1251" => "utf-8"),
            "fio_rp" => array("windows-1251" => "utf-8"),
            "work_current" => array("windows-1251" => "utf-8"),
            "work_proposed" => array("windows-1251" => "utf-8"),
            "stud_num" => array("windows-1251" => "utf-8"),
            "telephone" => array("windows-1251" => "utf-8"),
            "comment" => array("utf-8" => "utf-8"),
        ),
        "study_forms" => array(
            "name" => array("windows-1251" => "utf-8"),
            "name_short" => array("windows-1251" => "utf-8"),
        ),
        "study_groups" => array(
            "name" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "study_marks" => array(
            "name" => array("windows-1251" => "utf-8"),
            "name_short" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "subjects" => array(
            "name" => array("windows-1251" => "utf-8"),
            "name_short" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "task_menu_names" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "tasks" => array(
            "name" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "time_parts" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "time" => array(
            "place" => array("windows-1251" => "utf-8"),
            "length" => array("windows-1251" => "utf-8"),
        ),
        "time_kind" => array(
            "name" => array("windows-1251" => "utf-8"),
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "trip_houses" => array(
            "name" => array("windows-1251" => "utf-8"),
            "name_short" => array("windows-1251" => "utf-8"),
        ),
        "type_nauch_rab" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "towns" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
        "uch_org_rab" => array(
            "prim" => array("windows-1251" => "utf-8"),
            "vid_otch" => array("windows-1251" => "utf-8"),
        ),
        "uch_vosp_rab" => array(
            "prim" => array("windows-1251" => "utf-8"),
        ),
        "user_groups" => array(
            "comment" => array("windows-1251" => "utf-8"),
        ),
        "users" => array(
            "FIO" => array("windows-1251" => "utf-8"),
            "FIO_short" => array("windows-1251" => "utf-8"),
            "status" => array("windows-1251" => "utf-8"),
        ),
        "zakl" => array(
            "msg" => array("windows-1251" => "utf-8"),
        ),
        "zvanie" => array(
            "name" => array("windows-1251" => "utf-8"),
        ),
    );

    private function connectToDb() {
        mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
        mysql_select_db(DB_DATABASE) or die(mysql_error());
        mysql_query("SET NAMES UTF8") or die(mysql_error());

        print "Соединение с базой установлено\n";
    }
    private function backupTables() {
        $res = mysql_query("SHOW TABLES") or die(mysql_error());
        while (($row = mysql_fetch_assoc($res)) !== false) {
            $tableName = $row["Tables_in_".DB_DATABASE];
            if (mb_strpos($tableName, "backup_of_") === false) {
                print ".. копирую таблицу ".$tableName."\n";
                mysql_query("RENAME TABLE ".$tableName." TO backup_of_".$tableName) or die(mysql_error());
            }
        }
    }
    private function convertTables() {
        print "Конвертация таблиц\n";
        $res = mysql_query("SHOW TABLES") or die(mysql_error());
        while (($row = mysql_fetch_assoc($res)) !== false) {
            $tableName = $row["Tables_in_".DB_DATABASE];
            // получаем запрос создания таблицы
            $createQR = mysql_query("SHOW CREATE TABLE ".$tableName) or die(mysql_error());
            $createQ = mysql_fetch_assoc($createQR);
            $createQ = $createQ["Create Table"];
            if (mb_strpos($createQ, "DEFAULT CHARSET=utf8") !== false) {
                // пропускаем таблицы в кодировке UTF8
                print ".. таблица ".$tableName." будет пропущена, уже в utf8\n";
                $this->renameBack($tableName);
            } else {
                // конвертируем остальные таблицы в UTF8
                // вместе с данными
                $this->convertTable($tableName);
            }
        }
    }
    private function convertTable($tableName) {
        // еще раз получим запрос создания таблицы
        $createQR = mysql_query("SHOW CREATE TABLE ".$tableName) or die(mysql_error());
        $createQ = mysql_fetch_assoc($createQR);
        $createQ = $createQ["Create Table"];

        $newTableName = substr($tableName, 10);

        print ".. конвертация таблицы ".$newTableName."\n";
        print "... создаем таблицу заново\n";

        // исключаем все ненужные параметры из запроса
        $createQ = str_replace(array(
            "CHARACTER SET cp1251 ",
            "COLLATE cp1251_bin ",
            "CHARACTER SET utf8 ",
            "COLLATE utf8_unicode_ci ",
            "COLLATE=cp1251_bin",
            "backup_of_"
        ), "", $createQ);

        // меняем кодировку на utf8
        $createQ = str_replace(array(
            "DEFAULT CHARSET=cp1251",
            "DEFAULT CHARSET=cp1256",
            "DEFAULT CHARSET=latin1"
        ), "DEFAULT CHARSET=utf8", $createQ);

        mysql_query($createQ) or die(mysql_error());

        print "... вставляем в таблицу данные\n";
        print ".... выключаем проверку ключей\n";
        mysql_query("ALTER TABLE ".$newTableName." DISABLE KEYS") or die(mysql_error());
        print ".... получаем данные в кодировке CP1251\n";
        mysql_query("SET NAMES cp1251") or die(mysql_error());
        // $dataRes = mysql_query("SELECT * FROM ".$tableName." order by rand() limit 0,5") or die(mysql_error());
        $dataRes = mysql_query("SELECT * FROM ".$tableName."") or die(mysql_error());

        print ".... вставляем данные обратно в кодировке UTF8\n";
        mysql_query("SET NAMES UTF8") or die(mysql_error());
        $errors = array();
        while (($dataRow = mysql_fetch_assoc($dataRes)) !== false) {
            $dataRow = $this->convertData($newTableName, $dataRow);
            if (is_string($dataRow)) {
                $errors[] = $dataRow;
            } else {
                $fields = array();
                $values = array();
                foreach ($dataRow as $key=>$value) {
                    $fields[] = $key;
                    if (is_string($value)) {
                        $values[] = "'".mysql_real_escape_string($value)."'";
                    } else {
                        $values[] = $value;
                    }
                }
                $query = "INSERT INTO ".$newTableName." (".implode(", ", $fields).") VALUES (".implode(", ", $values).")";
                mysql_query($query) or die(mysql_error()." -> ".$query);
            }
        }
        if (count($errors) > 0) {
            var_dump($errors);
            mysql_query("DROP TABLE ".$newTableName) or die(mysql_error());
            die("Конвертация прервана");
        }

        print ".... включаем проверку ключей\n";
        mysql_query("ALTER TABLE ".$newTableName." ENABLE KEYS") or die(mysql_error());

        print ".... удаляем временную таблицу\n";
        mysql_query("DROP TABLE ".$tableName) or die(mysql_error());
    }

    /**
     * Обработка очень особых случаев
     *
     * @param $tableName
     * @param array $data
     * @return array
     */
    private function specialCases($tableName, array $data) {
        if ($tableName == "news") {
            if ($data["id"] == "821968") {
                // глючная запись, в одном поле несколько кодировок
                $data["file"] = "<p>Зачет по дисциплине CASE-технологии проектирования ИУС состоится в субботу 28 декабря в 11.00. Собираться возле аудитории 6-318а<br></p>";
            }
        }
        return $data;
    }
    private function convertData($tableName, array $data) {
        $data = $this->specialCases($tableName, $data);
        foreach ($data as $fieldName=>$fieldValue) {
            $from = "";
            $to = "";
            // пробуем угадать
            if ($from == "" || $to == "") {
                if (mb_detect_encoding($fieldValue) == "windows-1251") {
                    $from = "Windows-1251";
                    $to = "UTF-8";
                } elseif (mb_detect_encoding($fieldValue) == "ASCII") {
                    $from = "ASCII";
                    $to = "UTF-8";
                }
            }
            // не удалось, смотрим предопределенные параметры
            if ($from == "" || $to == "") {
                if (array_key_exists($tableName, $this->convertions)) {
                    $rule = $this->convertions[$tableName];
                    if (array_key_exists($fieldName, $rule)) {
                        $convertion = $rule[$fieldName];
                        if (count($convertion) == 1) {
                            foreach ($convertion as $f=>$t) {
                                $from = $f;
                                $to = $t;
                            }
                        } else {
                            die("Два разных быть не может, используйте специальные случаи");
                        }
                    }
                }
            }
            // если все равно ничего не определено, ругаемся
            if ($from == "" || $to == "") {
                var_dump($data);
                return ("В таблице ".$tableName." значение поля ".$fieldName." в кодировке ".mb_detect_encoding($fieldValue).". Нужно правило конвертации - ".$data[$fieldName]);
            } else {
                // кодировки определены, конвертируем
                $data[$fieldName] = mb_convert_encoding($data[$fieldName], $to, $from);
            }
        }
        return $data;
    }
    private function renameBack($tableName) {
        mysql_query("RENAME TABLE ".$tableName." TO ".substr($tableName, 10)) or die(mysql_error());
    }

    public function convert() {
        echo '<pre>';

        $this->connectToDb();
        $this->backupTables();
        $this->convertTables();

        print "Конвертация завершена\n";
    }
}

$converter = new DatabaseConverter();
$converter->convert();