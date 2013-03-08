<?php
//интеграция с Mantis
//через соотнесение пользователя Mantis пользователю портала АСУ

//таблица соотнесения в БД АСУ
$asu_mantis_inter='mantis_user_table';

$mantis_path='../mantisbt/';
$mantis_user_table='mantis_user_table';

include_once($mantis_path.'config_inc.php');

if (isset($_SESSION['id']) && intval($_SESSION['id'])>0)
{
    $asu_user_id=intval($_SESSION['id']);
	if(mysql_connect($g_hostname,$g_db_username,$g_db_password) && mysql_select_db($g_database_name))
     //проверка соединения с БД mantis
     {
        //поиск связки пользователя для автоматической авторизации в Mantis
        $query="select m.cookie_string
                from {$g_database_name}.{$mantis_user_table} m
                    left join {$sql_base}.{$asu_mantis_inter} a on a.mantis_user_id=m.id
                where a.asu_user_id=".$asu_user_id;
        
        $cookie_string=getScalarVal($query);
        //удаление старой авторизации Мантис, если существует
        if (isset($_COOKIE['MANTIS_STRING_COOKIE']) && $_COOKIE['MANTIS_STRING_COOKIE']!='')
            setcookie('MANTIS_STRING_COOKIE','',time()-3600,'');
            
        //проставление текущей метки авторизации    
        if ($cookie_string!='')
            {
                setcookie('MANTIS_STRING_COOKIE',$cookie_string,0,'/');
            }
     }
     else $msg.='<div class=warning>не могу соединиться с БД Mantis. Интеграция не произведена</div>';

    
    
}
?>