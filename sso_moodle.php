<?php
//$head_title='Авторизация в Moodle.';

// работает в IE 6-8 с внешними Moodle (на разных адресах) с версией jquery 1.4.2 (1.5.1 - не работает, ошибка "No Transport")
// в Opera с внешними Moodle выдает ошибку "ReferenceError: Security violation" (1.5.1 - не работает, ошибка "No Transport")
// в FireFox с внешними Moodle  не работает, текст ошибки не выдает
// ограничения связаны с политикой безопасности при cross-domain AJAX request
// в качестве решения может использоваться php-proxy (Simple PHP Proxy:http://benalman.com/code/projects/php-simple-proxy/)

include ('authorisation.php');


/*
// указать в заголовках принимающего сервера для выполнения требований безоспасноти

header('Access-Control-Allow-Origin: '.$moodlePath);  //http://moodle.ugatu.su  //$moodlePath
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control: *');	// разрешить взаимодействие с внешним сторонним сайтом (Moodle) для FireFox3+
header('Cache-Control: no-cache');  
header('Pragma: no-cache');  
*/


$master_user_id=$_SESSION['id'];      //идентификатор пользоватлея из сессии
$slave_system_id=1;     //идентификатор Сервиса на портале

//--------начало---- вывод для ajax-заппроса POST, будет выведен только через alert(html)
if (isset($_POST) && $_POST['username']!='')
{
    header('Content-Type: text/html; charset=windows-1251');

    $slaveUser=array('id'=>'','name'=>'','psw'=>'');
    include_once('funcs_php_crypt.php');    //библиотека симметричного шифрования
    
    $slaveUser['id']=intval($_POST['userid']);
    $slaveUser['name']=$_POST['username'];
    $slaveUser['psw']=$_POST['password'];
    
    $msg.='<p>';
    
    if ($slaveUser['id']>0 )    {
    
    $userExist=getScalarVal('select count(*) from `'.$sql_base.'`.sso_user_table
                            where master_user_id='.$master_user_id.'
                            and slave_system_id='.$slave_system_id.' ');
    if (intval($userExist)>0)   // обновить сведения о Сервисе пользователя
        $query='update `'.$sql_base.'`.sso_user_table set
            slave_user_id="'.$slaveUser['id'].'", 
            slave_user_hash="'.$slaveUser['name'].'", 
            slave_psw_hash="'.encodeKey($slaveUser['psw'],$sso_salt).'" 
            where  master_user_id='.$master_user_id.' and slave_system_id='.$slave_system_id.'';
    else    // добавить сведения о Сервисе пользователя
    $query='insert into `'.$sql_base.'`.sso_user_table(master_user_id,slave_system_id,
            slave_user_id,slave_user_hash,slave_psw_hash)
            values ('.$master_user_id.','.$slave_system_id.',"'.$slaveUser['id'].'",
            "'.$slaveUser['name'].'","'.encodeKey($slaveUser['psw'],$sso_salt).'")';
    
    
    if (mysql_query($query) && mysql_affected_rows()>0 )
        $msg.='Данные авторизации сохранены\обновлены в сервисе SSO';
    else $msg.='Ошибка сохранения: пользователь не добавлен\обновлен в SSO';   
    }
    else
    {$msg.='Ошибка сохранения: пользователь не найден в БД Moodle';}
    
    //$msg = mb_convert_encoding($msg, "UTF-8","cp1251");
    die($msg);
    
}
if (isset($_GET) && $_GET['exit']=='1')    //удалить рег.данные из хранилища   
{
    header('Content-Type: text/html; charset=windows-1251');

    $msg.='<br/>';
    
    $query='delete from `'.$sql_base.'`.sso_user_table
        where master_user_id='.$master_user_id.'
        and slave_system_id='.$slave_system_id.' ';
    if (mysql_query($query) && mysql_affected_rows()>0 )
        $msg.='Данные авторизации удалены из хранилища SSO';
    else $msg.='Ошибка удаления: данные авторизации не удалены из хранилища SSO';   
    
    //$msg = mb_convert_encoding($msg, "UTF-8","cp1251");
    die($msg);
}
//--------конец---- вывод для ajax-заппроса POST, будет выведен только через alert(html)
include ('master_page_short.php');

?>

<script language="JavaScript" src="scripts/moodle_auth.js"></script>
<script language="JavaScript">
var moodle_path='<?php echo $moodlePath; ?>';

//сохранить авторизацю в БД
function saveLogin()
{
    var userName=$("input[name*='username']").val();
    var userPsw=$("#password").val();
    var userid=$("#userid").val();
    $('#ac_loading').attr("style","");
    if (userName!='') {        
        $.ajax({
            type: "POST",
            url: "sso_moodle.php",
            data: "username="+userName+"&password="+userPsw+"&userid="+userid,
            success: function(html){
                var err=false;  //флаг ошибки
                if (typeof(msg)=='undefined') var msg;
                msg=html;
                
                if (msg.indexOf('Ошибка')!=-1) err=true;
                print_msg(err,msg,'add');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {			   
               print_msg(true,'<div>сохранение интеграции не возможно ('+
                    (errorThrown!=null?errorThrown:textStatus)+'), обратитесь к Администратору ...</div>','add'); 
               
            }
        });
    }
    $('#ac_loading').attr("style","display:none;");
}
 //проверить текущий статус авторизации
function getCurLogin()  
{        
        $('#ac_loading').attr("style","");
            
        $.ajax({        
            type: "POST",
			url: moodle_path+"/login/index.php",        
            success: function(html){
                var err=false;  //флаг ошибки
                var msg=''; //текст ошибки                      
                // +попытка релогина                                
                getUserStatus(err,msg,html,true,true); 
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {			   
               print_msg(true,'интеграция не возможна ('+
                    (errorThrown!=null?errorThrown:textStatus)+'), обратитесь к Администратору ...'); 
               $('#ac_loading').attr("style","display:none;");
            }
        }); 

}
//закрыть авторизацию (выход)
function getExitLogin(sesskey)   
{        
        $('#ac_loading').attr("style","");
        $.ajax({        
            url: moodle_path+"/login/logout.php?sesskey="+sesskey,        
            success: function(html){
                var err=false;  //флаг ошибки
                var msg=''; //текст ошибки          
                getUserStatus(err,msg,html);
                    // удалить рег.данные из хранилища       
                    $.ajax({        
                        url: "sso_moodle.php?exit=1",        
                        success: function(html){
                            var err=false;  //флаг ошибки
                            var msg=html; //текст ошибки                                      
                            print_msg(true,msg,'add');                
                        }
                    });                
            }
        });                      
}
// авторизовать пользователя по кнопке
function userlogin()
{
    var userName=$("input[name*='username']").val();
    var userPsw=$("#password").val(); 
	autologin(userName,userPsw,'saveLogin()');
	
}
</script>
<?php
echo '<h4>'.$pg_title.'</h4>'; 
?>

<p>Регистрационные данные пользователя Moodle</p>
<div id="moodleForm" class="text" name="moodleForm">
    Введите для авторизации данные в Moodle: 
    <p><input type="text" name="username" size="15" id="username" /> имя </p>
    <p><input type="password" name="password" size="15" id="password" /> пароль</p>
    <p><input type="button" name="Autologin" value="Вход" onclick="javascript:userlogin();" />    
    <input type="hidden" name="userid" value="" id="userid" />
</div>
<span id="ac_loading" class="cascadeSelect_loading" style="display:none";> </span>
<p>
<span id=msg></span>

<script language="javascript">
<?php

// получени регистрационных данных авторизации из хранилища
        $userMoodle=getRowSqlVar('select slave_user_hash as user,slave_psw_hash as psw
                                 from asu.sso_user_table where master_user_id='.$master_user_id.'
                                 and slave_system_id='.$slave_system_id.' limit 0,1');		
        if (is_array($userMoodle) && trim($userMoodle[0]['user']!=''))
        {
	include_once 'funcs_php_crypt.php';
        ?>
    var moodle_path='<?php echo $moodlePath; ?>';
    var userName='<?php echo $userMoodle[0]['user']; ?>';
    var userPsw='<?php echo decodeKey($userMoodle[0]['psw'],$sso_salt); ?>';        
        <?php
        }
//--------------------------- конец восстановления рег-х данных
    ?>
getCurLogin();
</script>


<div class=text><strong>Примечание:</strong>
<ul>
<li>перед автоматической авторизацией Moodle на Портале произведите "выход" в самом Moodle;</li>
<li>для авторизации на Портале Вам потребуется ввести Ваши учетные данные Moodle;</li>
<li>при успешной авторизации данные будут сохранены и использованы при авторизации на Портале
    <strong>без необходимости авторизации</strong> в Moodle;</li>
<li>для смены пользователя Moodle укажите "Выход" и введите новые имя и пароль;</li>
<li>Вы можете отказаться от хранения авторизации Moodle на Портале выбрав "выход"; </li>
<li>Вы так же можете использовать ручную авторизацию на самом Moodle;</li>
<li>тип связи "пользователь Портала" -> "пользователь Moodle"= многие:1.</li>
</ul>    
</div>
<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>