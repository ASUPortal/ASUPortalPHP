<?php
//сбор статистики по посещениям страниц сервера...

$agent=$_SERVER["HTTP_USER_AGENT"];
$host_ip=$_SERVER["REMOTE_ADDR"];
$host_name=@$_SERVER["REMOTE_HOST"];
//if ($host_name=='') $host_name=gethostbyaddr($host_ip);
$port=$_SERVER["REMOTE_PORT"];
////$_SERVER["REQUEST_URI"];
$url=basename($_SERVER["SCRIPT_NAME"]);//$_SERVER["REQUEST_URI"];
//$date_come=date('Y-m-d');//'12.11.2006 12:11:23';
//$time_come=date('H:i:s');//'12.11.2006 12:11:23';
$q_string=$_SERVER['QUERY_STRING'];
//$referer=preg_replace('/(^http:\/\/)([^\/]+)(.*)/i','${2}',$_SERVER['HTTP_REFERER']);	//выделение только имени хоста
$referer=@$_SERVER['HTTP_REFERER'];
$is_bot=0;	//бот_поисковик

//роботы поисковики';
$bot_pattern='/Mail.Ru|Twiceler|Googlebot|Yandex|StackRambler|bot|Dolphin/i';
if (preg_match($bot_pattern,$agent)) $is_bot=1;

$user_name='';
if (!isset($root_folder)) {$root_folder='';}

if (isset($_SESSION['auth']) && $_SESSION['auth']==1) {$user_name=$_SESSION['id'];}
$ip_not_stats='';
//$ip_not_stats=$_SERVER['SERVER_NAME'];	//указать IP-сервера или другого IP, кот. не учитывать в статистике...

if ($ip_not_stats!=$host_ip) 	{
	if (!isset($_SESSION['stats']) or $_SESSION['stats']!=($host_ip.' '.$url.'?'.$q_string)) 
	{//если первый раз зашли на страницу - запоминаем, чтобы не учитывать переходы на странице и ее обновления
		$_SESSION['stats']=$host_ip.' '.$url.'?'.$q_string;
		$query='insert into `stats`(url,host_ip,host_name,port,agent,user_name,q_string,referer,is_bot)
			values("'.$url.'","'.$host_ip.'","'.$host_name.'","'.$port.'","'.$agent.'","'.$user_name.'","'.$q_string.'","'.$referer.'","'.$is_bot.'")';
		//echo $query;
		mysql_select_db($sql_stats_base);
		mysql_query($query);
		mysql_selectdb($sql_base);
//echo '$query='.$query;
	/*	if ($res) {echo 'статистика дополнена.';}
		else {echo 'ошибки пополнения статистики';}*/
	//echo '<hr>query='.$query;
	
	}						}
?>