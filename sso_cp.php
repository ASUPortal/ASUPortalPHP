<?php
// настроечный файл интегратора автономных веб-систем

// Опера поддерживает только интеграцию в папке основного приложения (из. кот.запускается интегратор, например /asu/)
// FireFox поддерживает интеграцию внутри одного сервера (приложения с одинаковым ip-адресом)
// IE поддерживает интеграцию с внешними серверами (с разными адресами)

include ('authorisation.php');

$master_user_id=$_SESSION['id'];      //идентификатор пользоватлея из сессии
$slave_system_id=0; //3;     //идентификатор Сервиса на портале
if (isset($_GET['slsys_id'])) $slave_system_id=intval($_GET['slsys_id']);

// массив параметров интеграции системы, путь, страницы ...
$SlaveSystem=getRowSqlVar("select * from sso_systems where id=".$slave_system_id);

if ($slave_system_id>0 && is_array($SlaveSystem) ) {
    //--------начало---- вывод для ajax-заппроса POST, будет выведен только через alert(html)
    if (isset($_POST) && $_POST['username']!='')
    {
        
        include_once('funcs_php_crypt.php');    //библиотека симметричного шифрования
        
        $slaveUser=array('id'=>'','name'=>'','psw'=>'');
        $slaveUser['name']=$_POST['username'];
        $slaveUser['psw']=$_POST['password'];
        
        $msg.='<p>';
        
        $userExist=getScalarVal('select count(*) from `'.$sql_base.'`.sso_user_table
                                where master_user_id='.$master_user_id.'
                                and slave_system_id='.$slave_system_id.' ');
        if (intval($userExist)>0)   // обновить сведения о Сервисе пользователя
            $query='update `'.$sql_base.'`.sso_user_table set            
                slave_user_hash="'.$slaveUser['name'].'", 
                slave_psw_hash="'.encodeKey($slaveUser['psw'],$SlaveSystem[0]['psw_salt']).'" 
                where  master_user_id='.$master_user_id.' and slave_system_id='.$slave_system_id.'';
        else    // добавить сведения о Сервисе пользователя
        $query='insert into `'.$sql_base.'`.sso_user_table(master_user_id,slave_system_id,
                slave_user_hash,slave_psw_hash)
                values ('.$master_user_id.','.$slave_system_id.',
                "'.$slaveUser['name'].'","'.encodeKey($slaveUser['psw'],$SlaveSystem[0]['psw_salt']).'")';
        
        
        if (mysql_query($query) && mysql_affected_rows()>0 )
            $msg.='Данные авторизации сохранены\обновлены в сервисе SSO';
        else $msg.='Ошибка сохранения: пользователь не добавлен\обновлен в SSO';   
    
    
        $msg = mb_convert_encoding($msg, "UTF-8","cp1251");
        die($msg);
        
    }
    if (isset($_GET) && $_GET['exit']=='1')    //удалить рег.данные из хранилища   
    {
        $msg.='<br/>';
        
        $query='delete from `'.$sql_base.'`.sso_user_table
            where master_user_id='.$master_user_id.'
            and slave_system_id='.$slave_system_id.' ';
        if (mysql_query($query) && mysql_affected_rows()>0 )
            $msg.='Данные авторизации удалены из хранилища SSO';
        else $msg.='Ошибка удаления: данные авторизации не удалены из хранилища SSO';   
        
        $msg = mb_convert_encoding($msg, "UTF-8","cp1251");
        die($msg);
    }
}
//--------конец---- вывод для ajax-заппроса POST, будет выведен только через alert(html)

include ('master_page_short.php');

if ($slave_system_id>0 && is_array($SlaveSystem) ) {

?>

<script language="JavaScript" src="scripts/cp_auth.js"></script>
<script language="JavaScript">
<?php

?>

var SlaveSystemPath="<?php echo $SlaveSystem[0]['path']; ?>";
var SlaveSystemRespTemp="<?php echo $SlaveSystem[0]['response_templ']; ?>";  // ссылка_выхода, имя_пользователя
var SlaveSystemLoginPH="<?php echo $SlaveSystem[0]['login_pg_path']; ?>";
var SlaveSystemLogoutPH="<?php echo $SlaveSystem[0]['logout_pg_path']; ?>";
var SlaveSystemId="<?php echo $slave_system_id; ?>";
var SlaveSystemProfile="<?php echo $SlaveSystem[0]['profile_link']; ?>";  // ссылка на профиль пользователя

    
// авторизовать пользователя по кнопке
function userlogin()
{
    var userName=$("input[name*='username']").val();
    var userPsw=$("#password").val(); 
    
    autologin(userName,userPsw,'saveLogin("'+userName+'","'+userPsw+'","sso_cp.php?slsys_id='+SlaveSystemId+'")');
	
}
</script>
<?php
echo '<h4>'.$pg_title.'</h4>'; 
?>
<p><img src="<?php echo $SlaveSystem[0]['logo_img']; ?>" height=40 alt="<?php echo $SlaveSystem[0]['name']; ?>" title="<?php echo $SlaveSystem[0]['comment']; ?>" style="padding-top:-10px; margin-top:-10px;"> &nbsp; 
<?php echo $SlaveSystem[0]['comment']; ?>
</p>
<p class=text>Регистрационные данные пользователя системы</p>
<div class=forms_under_border style="padding:10px;">
    <div id="loginForm" class="forms_under_border" >
        Введите для авторизации данные в системе: 
        <p><input type="text" name="username" size="15" id="username" /> имя </p>
        <p><input type="password" name="password" size="15" id="password" /> пароль</p>
        <p><input type="button" name="Autologin" value="Вход" onclick="javascript:userlogin();" />    
        <input type="hidden" name="userid" value="" id="userid" />
    </div>
    <span id="ac_loading<?php echo $slave_system_id; ?>" class="cascadeSelect_loading" style="display:none";> </span>
    <p>
    <span id="msg<?php echo $slave_system_id; ?>"></span>
</div>
<script language="javascript">
<?php

// получени регистрационных данных авторизации из хранилища
        $userSlaveSystem=getRowSqlVar('select slave_user_hash as user,slave_psw_hash as psw
                                 from asu.sso_user_table where master_user_id='.$master_user_id.'
                                 and slave_system_id='.$slave_system_id.' limit 0,1');		
        if (is_array($userSlaveSystem) && trim($userSlaveSystem[0]['user']!=''))
        {
	include_once 'funcs_php_crypt.php';
        ?>
    //var SlaveSystemPath='<?php echo $cpPath; ?>';
    var userName='<?php echo $userSlaveSystem[0]['user']; ?>';
    var userPsw='<?php echo encodeKey($userSlaveSystem[0]['psw'],$SlaveSystem[0]['psw_salt']); ?>';        
        <?php
        }
//--------------------------- конец восстановления рег-х данных
    ?>
getCurLogin(true);
</script>
<?php }
else echo '<div class=warning>Ошибка получения настроек системы. Интеграция прекращена.</div>';
?>

<div class=text><strong>Примечание:</strong>
<ul>
<!--li>перед автоматической авторизацией Moodle на Портале произведите "выход" в самой Системе;</li-->
<li>для автоматической авторизации на Портале Вам потребуется ввести Ваши учетные данные удаленной системы;</li>
<li>при успешной авторизации данные будут сохранены и использованы при авторизации на Портале
    без необходимости <strong>повторной авторизации</strong> в системе;</li>
<li>для смены пользователя Системы укажите "Выход" и введите новые имя и пароль;</li>
<li>Вы можете отказаться от хранения авторизации Системы на Портале выбрав "выход"; </li>
<li>Вы так же можете использовать ручную авторизацию в самой Системе;</li>
<li>тип связи "пользователь Портала" -> "пользователь Системы"= многие:1, т.е. несколько пользователей Портала могут ссылаться на одного пользователя удаленной Системы.</li>
</ul>    
</div>
<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>