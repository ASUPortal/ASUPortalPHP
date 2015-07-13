<?php

$showWeekCnt=true;  //отражать число недель в заголовке

$name_days=array('Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота');

$DateTimeShift=mktime(date("H")+$timeHcorrect, date("i"), date("s"), date("m")  , date("d"), date("Y"));	//сдвиг времени с учетом часовго пояса

$today=getdate($DateTimeShift);

$daysBeetween=floor(($DateTimeShift-$dayBegin)/(60*60*24));     //число прошедших дней
$weeksBeetween=floor($daysBeetween/7)+1;
$tabs6="&nbsp;&nbsp;&nbsp;&nbsp;";

$dateTimeOut= "<b>Сегодня: </b>".$tabs6.$name_days[$today['wday']]." ".$tabs6.
date("d.m.Y",$DateTimeShift)." <b>".
($showWeekCnt?"<font size=+2>".$tabs6.$weeksBeetween."</font>-я учебная неделя</b>":"")."<br>";

if (isset($_SESSION['auth']) && $_SESSION['auth']==1) {
 	$dateTimeOut=$dateTimeOut."<div class=warning style='text-align:center;font-size:10pt;'><a class=warning href='{$files_path}_modules/_lecturers/index.php?action=view&id=".$_SESSION['id']."' title='моя страница'>".$_SESSION['FIO']."</a> &nbsp; <a class=warning href='".$files_path."p_administration.php?exit=1'>выход</a></div>";}
else {
if (!isset($_GET['wap'])) {
$dateTimeOut=$dateTimeOut."<a class=warning href='#show' style='font-size:12pt;' onClick=javascript:show_auth_form('author_layer');>авторизация</a>
<div name='author_layer' id='author_layer' style='position:absolute; top: 70px; display:none;width:300'>
<table width='250' border='1' style='background: #ffffff; ' cellspasing='10' cellpadding='10' align='center'>
  <tr>
    <td width='50%'>
<div class='text'><p align='center'><strong>Авторизация пользователя</strong></p>
	<form action='".$files_path."p_administration.php' method='post'>
		Логин <span class=warning>*</span><br><input type='text' name='login'><br>
	    Пароль <span class=warning>*</span><br><input type='password' name='password'>".
	    ($saveLogin?"<br><label><input type=checkbox name='saveAuth' id='saveAuth'>запомнить на 2 недели </label>":"")."<br>
	    <a href='/".$root_folder."_modules/_acl_manager/?action=restorePassword'>Восстановление пароля</a><br><br>
		<input type='submit' value='Вход' class='button'>&nbsp;&nbsp;&nbsp;&nbsp;
	    <input type='reset' value='Отмена' class='button' onClick=javascript:hide_show('author_layer');>&nbsp;&nbsp; 
	    
    </form></div></td>
  </tr>
</table>
</div>";
}}