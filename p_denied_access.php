<?php
//session_start();
$pg_title='Ошибка доступа';

//if (!isset($files_path)) {$files_path='';}

//include $files_path.'header.php';
include 'sql_connect.php';

include 'header.php';



$errMsgArr=array (
	'not_auth'=>'',
	'user_lock'=>'Ваша группа <span class=warning> заблокирована.</span> Обратитесь к администратору портала .',
	'not_task_auth'=>'Вы авторизованы, но Ваш доступ ограничен.<br><a href="p_administration.php">Список Ваших задач портала</a>',
	''=>'Тип ошибки неопределен.Обратитесь к администратору портала',
	'self_load'=>'Ошибка доступа.Обратитесь к администратору портала'
	);
echo $head;

$errMsg='';$typeErr='';
if (isset($_GET['type']))
{
 $typeErr=$_GET['type'];
 if ($typeErr=='not_auth') 
 	{ $url='';
	  if (isset($_GET['url'])) {
			//$url=str_replace('?',	   	
		   $url='?url='.$_GET['url'];
	   	
		   }
	  $errMsg='У Вас нет доступа к этой странице, т.к. Вы не авторизованы. 
	  Пройдите <a title="возможно срок Вашей сессии истек и потребуется заново пройти авторизацию." 
	  href="p_administration.php'.$url.'">авторизацию...</a>';}
 if ($typeErr=='user_lock')  		{$errMsg=$errMsgArr['user_lock'];}
 if ($typeErr=='not_task_auth')  	{$errMsg=$errMsgArr['not_task_auth'];}
 if ($errMsg=='') 					{$errMsg=$errMsgArr[''];}
} 
else {$errMsg=$errMsgArr['self_load'];}

echo '<h4>'.$errMsg.'</h4>';
echo $end1;
include $files_path."display_voting.php";
define("CORRECT_FOOTER", true);
echo $end2; include('footer.php'); 

?>
