<?php
include ('../sql_connect.php');

$files_path='http://'.$_SERVER['SERVER_NAME'].'/'.$root_folder;
include ('../header.php');

echo $head;
?>
<style>
.button {border: solid black 1px; padding:2px; background-color:#EFEFFF;}
</style>
<div class="main">Ошибка 404.</div><br>
<h3 align="center">К сожалению запрошенной Вами страницы не существует.</h4>
<h4 align="center" >
	<a href=javascript:history.back(); class=button>вернуться назад</a> или 
	<a href="<?php echo $files_path;?>" class=button>перейти на главную страницу</a> 
</h4> 
<?php
if(isset($_SESSION['auth']) && $_SESSION['auth']==1) {
	
  echo '<div class=text align=center>Возможно эта станица возникла в случае ошибки. 
  		Вы как <span class=success>зарегистрированный</span> пользователь можете написать как она у Вас появилась.<br> 
  		<a href="'.$files_path.'mail.php?compose=1"> <u><b>Пишите</b></u></a> на имя Агапова Руслана Николаевича.<p>
  		
  		Отправив сообщение, Вы <u>поможете устранить</u> неточности в работе портала. Спасибо за участие.<p>
  		
  		Для отправки сообщения участнику портала используется отдельная задача <a href="'.$files_path.'mail.php"><u><b>"Сообщения"</b> </u></a>.<br>
		и Вам достоточно только быть зарегистрированным на портале, больше ничего.';	
}
$files_path='../';

//$files_path='http://'.$_SERVER['SERVER_NAME'].'/'.$root_folder;
  echo $end1;
  include ('../display_voting.php');
  echo $end2;
?>