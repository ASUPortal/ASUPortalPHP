<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 13.01.13
 * Time: 10:44
 * To change this template use File | Settings | File Templates.
 */
    require_once("core.php");
    $tables = array();
    // получаем список таблиц, которые нужно конвертировать
    $tables_res = mysql_query("show tables in ".DB_DATABASE) or die(mysql_error());
    while ($table = mysql_fetch_assoc($tables_res)) {
        $table = $table["Tables_in_".DB_DATABASE];
        // по каждой таблице получаем столбцы
        $cols_res = mysql_query("describe ".$table) or die(mysql_error());
        while ($col = mysql_fetch_assoc($cols_res)) {
            // если это varchar, то он нормально извлечется в utf8
            // если это текст, то только cp1251
            if ($col["Type"] == "text") {
                $tables[$table][$col["Field"]] = "cp1251";
            } elseif ($col["Type"] == "mediumtext") {
                $tables[$table][$col["Field"]] = "cp1251";
            } elseif (strpos(mb_strtolower($col["Type"]), "varchar") !== false) {
                $tables[$table][$col["Field"]] = "utf8";
            }
        }
    }
    // исключаем таблицы, с которыми уже все хорошо и которые трогать не надо
    unset($tables["acl_defaults"]);
    unset($tables["acl_tables"]);
    unset($tables["acl_tables_access_entries"]);
    unset($tables["acl_tables_access_users"]);
    unset($tables["backup_settings"]);
    unset($tables["backup_settings2"]);
    unset($tables["dashboard"]);
    unset($tables["help"]);
    unset($tables["help_access_entries"]);
    unset($tables["help_access_users"]);
    unset($tables["menu"]);
    unset($tables["menu_items"]);
    unset($tables["menu_items_access"]);
    unset($tables["pl_calendars"]);
    unset($tables["pl_corriculum"]);
    unset($tables["pl_corriculum_cycles"]);
    unset($tables["pl_corriculum_disciplines"]);
    unset($tables["pl_corriculum_discipline_controls"]);
    unset($tables["pl_corriculum_discipline_hours"]);
    unset($tables["pl_corriculum_discipline_labors"]);
    unset($tables["pl_events"]);
    unset($tables["pl_event_membership"]);
    unset($tables["pl_resources"]);
    unset($tables["print_field"]);
    unset($tables["print_form"]);
    unset($tables["print_formset"]);
    unset($tables["questions"]);
    unset($tables["questions_tickets"]);
    unset($tables["questions_tickets_questions"]);
    unset($tables["rating_index"]);
    unset($tables["rating_index_value"]);
    unset($tables["rating_person_indexes"]);
    unset($tables["seb_protocol"]);
    unset($tables["seb_protocol_members"]);
    unset($tables["seb_question"]);
    unset($tables["seb_question_in_ticket"]);
    unset($tables["seb_ticket"]);
    unset($tables["settings"]);
    unset($tables["study_activity_access_entries"]);
    unset($tables["study_activity_access_users"]);
    unset($tables["study_gradebook"]);
    unset($tables["taxonomy"]);
    unset($tables["taxonomy_terms"]);
    unset($tables["template_notification"]);
    unset($tables["user_password_requests"]);
    unset($tables["_bd_post"]);
    unset($tables["_bd_sdacha"]);
    unset($tables["_bd_student"]);
    unset($tables["_bd_theme_tmp_export"]);
    unset($tables["pchart"]);
    // таблицы портала, которые не конвертятся
    unset($tables["diploms"]);
    // для каждой таблицы отдельно извлекаем нужные столбцы и конвертим их
    foreach ($tables as $table=>$cols) {
        echo "Processing table ".$table."... ";
        mysql_query("start transaction") or die(mysql_error());
        // смотрим, сколько всего данных есть в таблице
        $cnt = 0;
        $res = mysql_query("select count(id) as cnt from ".$table) or die(mysql_error());
        $cnt = mysql_fetch_assoc($res);
        $cnt = $cnt["cnt"];
        $cursor = 0;
        while ($cursor < $cnt) {
            $data = array();

            foreach ($cols as $col=>$charset) {
                // извлекаем конвертируемые столбцы отдельно в нужной кодировке
                mysql_query("set names ".$charset) or die(mysql_error());
                $items = mysql_query("select id, ".$col." from ".$table." limit ".$cursor.", 500") or die(mysql_error());
                while ($row = mysql_fetch_assoc($items)) {
                    $data[$row["id"]][$col] = $row[$col];
                }
            }

            //  теперь данные конвертируем в utf8 и в utf8 сохраняем
            mysql_query("set names utf8") or die(mysql_error());
            $q = "";
            foreach ($data as $id=>$fields) {
                $q = "UPDATE ".$table." SET ";
                $f = array();
                foreach ($fields as $name=>$value) {
                    $f[] = " `".$name."` = '".mysql_real_escape_string($value)."' ";
                }
                $q .= implode(", ", $f);
                $q .= " WHERE `id` = ".$id;
                mysql_query($q) or die(mysql_error()." -> ".$q);
            }

            $cursor += 500;
        }
        mysql_query("ALTER TABLE ".$table." CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci") or die(mysql_error());
        mysql_query("rollback") or die(mysql_error());
        echo " ok<br>";
    }