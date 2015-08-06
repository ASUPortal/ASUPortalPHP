<?php 
function err_show($msg)
{
    global $admin_email,$saveLogin;
    $add_tmp='';
	if (isset($_GET['url'])) {
		$spec_char='%code-';
		$url=$_GET['url'];
			if (strpos($url,$spec_char)>=0) {
				$url=str_replace($spec_char.ord('?').$spec_char,'?',$url);
				$url=str_replace($spec_char.ord('=').$spec_char,'=',$url);
				$url=str_replace($spec_char.ord('&').$spec_char,'&',$url);
				//все символы 2-значные
			}
	 $add_tmp='?url='.$url;}
	
	echo '<div class="main">Авторизация пользователя</div><br>
    <div class="warning">'.$msg.'</div><br>
    <div class="text"><form action="p_administration.php'.$add_tmp.'" method="post">
    Логин <span class=warning>*</span><br><input type="text" name="login"><br>
    Пароль <span class=warning>*</span><br><input type="password" name="password">'.
    ($saveLogin?"<br><label><input type=checkbox name='saveAuth' id='saveAuth'>запомнить на 2 недели </label>":"").'<br><br>
    
    
    <input type="submit" value="Вход" class="button"> &nbsp;&nbsp;
    <input type="reset" value="Очистить" class="button"><br>
    </form>
	Сейчас автоматическая регистрация на портале не доступна. <br>
	Для получения логина и пароля отправьте заявку <a href=mailto:'.$admin_email.'><u>почтой</u></a> Администратору портала 
	</div>';
}
//---------------------------------------------
$login_status=false;
$result='';
$msg='';


include 'sql_connect.php';

$pg_title='Администрация';

  if(isset($_GET['exit']) && isset($_SESSION['auth']))
   {
    unset($_SESSION['auth']);
    unset($_SESSION['FIO']);
    unset($_SESSION['userType']);
    unset($_SESSION['photo']);
    unset($_SESSION['date_time']);
    unset($_SESSION['id']);
    unset($_SESSION['kadri_id']);	//связка с сотрудниками каф.
    unset($_SESSION['user_login']);
    unset($_SESSION['photo_act']);	//права на фотогалерею

    session_destroy();
//--------------------
        if (isset($_COOKIE['MANTIS_STRING_COOKIE']) && $_COOKIE['MANTIS_STRING_COOKIE']!='')
            {
		setcookie('MANTIS_STRING_COOKIE','',time()-3600,'/');		
	    }
	if (isset($_COOKIE[$saveLogin_cook]) && $_COOKIE[$saveLogin_cook]!='')
	    setcookie($saveLogin_cook,'',time()-3600,'/');	    
//---------------------------
    header("Location:index.php");
   }


if(!isset($_SESSION['auth']) || $_SESSION['auth']!=1)	//не авторизованы
 {
  $password='';$login='';
  $secret_str='';	//хеш-строка
  if (isset($_POST['password']))  $password=$_POST['password'];
  if (isset($_POST['login'])) $login=$_POST['login'];
  if ($saveLogin && isset($_COOKIE[$saveLogin_cook]) && $_COOKIE[$saveLogin_cook])
    $secret_str=substr(f_ri($_COOKIE[$saveLogin_cook]),0,32);
  
  
  if($secret_str=='' && (ereg("[[:punct:]]",$login) || ereg("[[:punct:]]",$password)))
   {
    $msg=('Логин и пароль могут содержать только буквы и цифры');
   }
  else
  if ($secret_str=='' && ($password=="" || $login=="") )
   {
	$msg=('Логин и пароль должны быть заполнены');
   }
   else {
   	// проверка числа и времени попыток, введение блокировок
      
        $query='select ua.login_cnt,ua.login_datetime from users u
			      left join `'.$sql_stats_base.'`.`user_activity` ua on ua.user_id=u.id
			      where u.login="'.$login.'" limit 0,1';
	$arr_attemp=getRowSqlVar($query);

	$timeLeft=(strtotime("now")-strtotime($arr_attemp[0]['login_datetime']) );
	//$timeLeft=floor($timeLeft);	//сколько прошло минут с последней попытки
	$login_cnt=$arr_attemp[0]['login_cnt'];	//число попыток
	$wait_sec_cnt=$login_cnt*2*60-$timeLeft;
	// первые 3 попытки без блокировки, с 4-й при росте числа попыток растет время до разблокировки
	//echo '<div align=right>wait_sec_cnt(c)='.$wait_sec_cnt.', $timeLeft(c)='.$timeLeft.',$login_cnt='.$login_cnt.'</div>';
	if ($login_cnt>3 && $wait_sec_cnt>0)	 {	    
	    $msg= 'Вы исчерпали число попыток авторизации. Разблокировка через: '.
		(floor($wait_sec_cnt/60)).' минут (в '.date("H:i", strtotime("now")+$wait_sec_cnt).')';
	    }
    	else {	// первичная проверка прошла и нет блокировок -> проверка введенных данных
	if (!$login_status)    { 	$msg=('Не правильно введены логин и пароль');  }
	
	//ведем учит числа попыток входа для временных блокировок и защиты учетной записи
	$query_log_attempt='update `'.$sql_stats_base.'`.`user_activity` ua
	    left join users u on ua.user_id=u.id 
	    set ua.login_cnt=ua.login_cnt+1, login_datetime=now() 
	    where u.login like "'.$login.'"';
	mysql_query($query_log_attempt);
	
   
   	$login_query='select u.id,u.login,u.FIO,u.kadri_id,ua.auth_datetime as date_time,ua.last_datetime,ua.last_page,status 
			      from users u
			      left join `'.$sql_stats_base.'`.`user_activity` ua on ua.user_id=u.id
			      where 1 ';
	if ($secret_str!='') $login_query.=' and ua.login_secret like "'.$secret_str.'"';
	else $login_query.=' and u.login="'.$login.'" and u.password="'.md5($password).'"';
	
	$login_query.=' limit 0,1';
	
	$result=mysql_query ($login_query);
	   
   	//	}
    if(mysql_num_rows($result)<=0)
    { 	if ($secret_str!='')
		{
		$msg='Строка сеанса устарела. Требуется пройти авторизацию.'; 
		setcookie($saveLogin_cook,'',time()-3600,'/');
		}
    }	
  	else 
   {
    $f=mysql_fetch_array($result);
    $result=mysql_query ("SELECT user_groups.name as group_name,user_groups.all_user_select,user_groups.id as group_id,
			 user_groups.blocked as group_blocked  
		FROM user_groups inner join user_in_group on user_in_group.group_id=user_groups.id 
		WHERE user_in_group.user_id='".$f['id']."' order by user_groups.all_user_select desc");
	$g=mysql_fetch_array($result);
	$_SESSION['auth']=1;
	$_SESSION['FIO']=$f['FIO'];
	$_SESSION['userType']=$f['status'];
	//$_SESSION['role']=$f['role'];
	$_SESSION['kadri_id']=$f['kadri_id'];
	$_SESSION['user_login']=$f['login'];
	$_SESSION['group_id']=$g['group_id'];	//ID группы пользователя
	$_SESSION['group_blocked']=$g['group_blocked'];	//ID группы пользователя
	$_SESSION['all_user_select']=$g['all_user_select'];

//-----------права на фотогалерею с учетом  Прав на портале----------
 		$query="SELECT distinct tig.task_rights_id 
			FROM task_in_group tig inner join tasks t on t.id=tig.task_id 
			WHERE tig.user_group_id in (
			  SELECT group_id
				FROM user_in_group
				WHERE user_id ='".$f['id']."') and t.url like '%_photo_cpg%' ";

		//введение персональных задач пользователя
		$query.="union 
			SELECT distinct tiu.task_rights_id 
			FROM task_in_user tiu inner join tasks t on t.id=tiu.task_id 
			WHERE user_id ='".$f['id']."' and t.url like '%_photo_cpg%'
			order by 1 desc limit 0,1";
		$task_rights_id=getScalarVal($query);
		//echo '$task_rights_id='.$task_rights_id.'<hr>'.$query;
		switch ($task_rights_id) {
		    case 3:
			$_SESSION['photo_act']='user';
			break;
		    case 4:
			$_SESSION['photo_act']='admin';
			break;
		    default:
			$_SESSION['photo_act']='guest';
		}
//-----------------------------------------------------
 	
	$result_photo=mysql_query ('select photo from kadri where id="'.$f['kadri_id'].'"');
	$tmp_photo=mysql_fetch_array($result_photo);
	$_SESSION['photo']=$tmp_photo['photo'];
	
	$_SESSION['date_time']=$f['date_time'];

	$_SESSION['id']=$f['id'];
	
	//запоминаем состояние авторизации пользователя на 14дней
	if ($saveLogin && isset($_POST['saveAuth']) && $_POST['saveAuth']=='on')	{
	    
	    $secret_str=md5(date("Y-m-d H:i:s").$password);
	    $day4save=14;	//2 недели
	    setcookie($saveLogin_cook,$secret_str,time()+60*60*24*$day4save,'/');
	    $query='update `'.$sql_stats_base.'`.`user_activity` set login_secret="'.$secret_str.'" where user_id='.$_SESSION['id'].' ';
	    mysql_query($query);
	}
	
	$qyery='update `'.$sql_stats_base.'`.`user_activity` set auth_datetime="'.date("Y-m-d H:i:s").'", login_cnt=0, login_datetime=NULL  where user_id="'.$f['id'].'"';
	$result1=mysql_query($qyery);
	if (mysql_affected_rows()==0) {
		$qyery='insert into `'.$sql_stats_base.'`.`user_activity`(`user_id`,`auth_datetime`)
			values("'.$_SESSION['id'].'","'.date("Y-m-d H:i:s").'")';
		mysql_query($qyery);
	}
	
	$query='';
	$login_status=true;
	$msg='';	//ошибок авторизации нет
	
	//------------------------  авторизация в Mantis
	if (isset($useMantis) && $useMantis)	include_once('_mantis_integ.php');	
	//--------------------------------------------------	
	
	if (isset($_GET['url']) && trim($_GET['url'])!="")
	    {header("Location:".$_GET['url']."");}
	else {header("Location:p_administration.php");}
	
	    
	}
	}
 }
 }
//include_once('_mantis_integ.php');

include 'header.php';

// <abarmin date="22.12.2012">
// мы пережили конец света!
if (!is_null(CSession::getCurrentUser())) {
    if (!is_null(CSession::getCurrentUser()->getPersonalSettings())) {
        if (CSession::getCurrentUser()->getPersonalSettings()->isDashboardEnabled()) {
            header("Location:_modules/_dashboard/");
        } else {
        	header("Location:_modules/_dashboard/index.php?action=tasks");
        }
    }
}
// </abarmin>

echo $head;

if(!isset($_SESSION['auth']) || $_SESSION['auth']!=1)	//не авторизованы
 {
   if ($msg!='') {err_show($msg);}
 } 
else  
 {

  include_once 'task_menu.php';
  echo'<br><div class="main">Консоль управления </div>
  <br>
  <div class="middle_lite">Здравствуйте, <b>'.$_SESSION['FIO'].',</b>';
  	 
if (isset ($_SESSION['group_blocked']) && $_SESSION['group_blocked']==1) 
{
	echo '<h4> Ваша группа <span class=warning> заблокирована </span>. Обратитесь к администратору портала </h4>';
} 
else {
  //print_r($_SESSION);//
  $query='select ua.`last_page`,`tasks`.`name` from `'.$sql_stats_base.'`.`user_activity` ua 
	left join `tasks` on `tasks`.`url`=ua.`last_page` where ua.`user_id`='.intval($_SESSION['id']);
  $res=mysql_query($query);
  $a=mysql_fetch_array($res);
  if ($a['name']=='') {$a['name']=$a['last_page'];}
  echo'<p>Последний раз вы были авторизованы: '.DateTimeCustomConvert($_SESSION['date_time'],'dt','mysql2rus').
  ' &nbsp; <a href="'.$a['last_page'].'" title="последняя посещенная страница">'.$a['name']."</a>";
  
  
  $query='SELECT count(*) as develop_news_cnt FROM `develop_news` 
  	where date_time>="'.$_SESSION['date_time'].'" 
	ORDER BY `id` DESC LIMIT 0 , 30';
  //echo $query;
  $result=mysql_query($query);
  $a=mysql_fetch_array($result);
  if ($a['develop_news_cnt']>0) 
  {echo '<a href="develop_news.php" title="обновления портала с момента Вашего отсутствия">
  	, обновлений портала: <b>'.$a['develop_news_cnt'].'</b> <img src="images/notice.gif" border=0></a>';}
  $a=null;$result=null;
  
  echo '.</div>';

  //---------------------------------------------
  //добавляем информацию о днях рождения сотрудников, только тем, у кого открыт доступ к своей анкете
  if (getTaskAccess($_SESSION['id'],'lect_anketa.php')) include 'kadri_bDays.php';	//именинники- преподаватели сегодня-завтра
    
  echo '<div class="middle_lite"> ';

  if (isset($_GET['sort'])) {
	$sort_id=$_GET['sort'];
   	if ($sort_id<1 || $sort_id>3) {$sort_id=1;}
   }
   else {$sort_id=1;}
  

$query='SELECT DISTINCT tasks.name,tasks.url,tasks.comment  
  	FROM tasks 
  	where tasks.hidden!=1 and tasks.id in (
	  		select task_id from task_in_group where user_group_id in (select group_id from user_in_group where user_id="'.$_SESSION['id'].'")
		  	union
		  	select task_id from task_in_user where user_id="'.$_SESSION['id'].'")
	  order by '.$sort_id.'';
	  		  
  $result=mysql_query($query);
  if (mysql_num_rows($result)>0) {

?>  <div class=text align=center> если у Вас есть вопросы по работе проекта <a href="mail.php?compose=1"> <u><b>пишите</b></u></a> на имя Администратора.<br>
  Для отправки сообщения участнику портала используется отдельная задача <a href="mail.php"><u><b>"Сообщения"</b> </u></a>.
  <?php if ($msg!='') echo  $msg; ?>
  <p>Задачи добавленные за последнюю неделю отмечены <b>*</b>
  </div>
<?php     
  echo '<div class=text>Сортировать задачи ('.mysql_num_rows($result).'): <a href="?sort=1"> по имени</a>, <a href="?sort=2"> по пути</a>, <a href="?sort=3"> по дате</a></div><p>';

 	//-------------------------------- SSO Сервисы начало ------------------------
	if (isset($useMoodle) && $useMoodle===true) include_once('_moodle_integ.php');
	if (isset($useIntegManager) && $useIntegManager===true) include_once('_cp_integ.php'); 

	//-------------------------------- SSO Сервисы конец ------------------------
	
	while ($a=mysql_fetch_array($result))		//список задач по группе пользователя
	{
	 	echo '<a href="'.$a['url'].'" title="'.$a['comment'].'">'.$a['name'].'</a><br> ';
	 
	 }
	 
	 if ($_SESSION['id']==28) 
	 	{
	echo '<p><a href="#show" onclick="javascript:hide_show(\'user_select\');"> .</a>';
		  echo '<div id=user_select name=user_select style="display:none;">';
		  	if (isset($view_all_mode)) $view_all_mode_tmp=$view_all_mode;
			$view_all_mode=true;
			persons_select('user_id');
			if (isset($view_all_mode_tmp)) $view_all_mode=$view_all_mode_tmp; 
			else unset($view_all_mode);
		  echo'</div>';
		  $user_id=''; 
		  if (isset($_GET['user_id'])) {
		   $user_id=$_GET['user_id'];
			  $result=mysql_query ('select u.id,u.login,u.fio,u.kadri_id,ua.auth_datetime as datetime,ua.last_datetime,ua.last_page,status 
			      from users u
			      left join `'.$sql_stats_base.'`.`user_activity` ua on ua.user_id=u.id
			      where u.id="'.$user_id.'"');
			  if(mysql_num_rows($result)==1)
			   {
			    $f=mysql_fetch_array($result);
			    $result=mysql_query ("SELECT user_groups.name as group_name,user_groups.id as group_id FROM user_groups inner join user_in_group on user_in_group.group_id=user_groups.id 
					WHERE user_in_group.user_id='".$f['id']."'");
				$g=mysql_fetch_array($result);
				$_SESSION['auth']=1; $_SESSION['FIO']=$f['FIO'];
				$_SESSION['kadri_id']=$f['kadri_id'];
				$_SESSION['userType']=$f['status'];
				$_SESSION['user_login']=$f['login'];
				$_SESSION['group_id']=$g['group_id'];	//ID группы пользователя
				
				$result_photo=mysql_query ('select photo from kadri where id="'.$f['kadri_id'].'"');
				$tmp_photo=mysql_fetch_array($result_photo);
				$_SESSION['photo']=$tmp_photo['photo'];

				$_SESSION['id']=$f['id'];

			    }
		   }
		}
	
  }
  else {echo error_msg('Нет активных задач. Обратитесь к Администратору портала. ');}
  echo '</div>';}
 }
    echo $end1;
    if (CSettingsManager::getSettingValue("display_voting_in_admin")) {
        include "display_voting.php";
    }
    echo $end2;
define("CORRECT_FOOTER", true);
    include('footer.php');
?>