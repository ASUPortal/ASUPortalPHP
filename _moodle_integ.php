<?php
//интеграция с Moodle через ajax-авторизацию, подробнее в sso_moodle.php

// отражать только для авторизованных пользователей
if (isset($_SESSION['id']) && intval($_SESSION['id'])>0)    
{
        //header('Content-Type: text/html; charset=windows-1251');
        //поиск связки пользователя для автоматической авторизации в Mantis
        $master_user_id=intval($_SESSION['id']);
        $slave_system_id=1;
        
        $userMoodle=getRowSqlVar('select slave_user_hash as user,slave_psw_hash as psw
                                 from `'.$sql_base.'`.sso_user_table
                                 where master_user_id='.$master_user_id.'
                                        and slave_system_id='.$slave_system_id.'
                                 limit 0,1');		
        if (is_array($userMoodle) && trim($userMoodle[0]['user']!=''))
        {
	// библиотека шифрования
        include_once 'funcs_php_crypt.php';
        ?>

<p class=text><img src="images/moodle-logo.gif" alt="moodle" title="moodle">
    <span id="ac_loading" class="cascadeSelect_loading" style="display:none";> </span>
    <span id=msg></span>
    <a href="sso_moodle.php" style="padding-left:40px;" class=success>настроить...</a></p>        

<script language="JavaScript" src="scripts/moodle_auth.js"></script>
<script language="JavaScript">
    var moodle_path='<?php echo $moodlePath; ?>';
    var userName='<?php echo $userMoodle[0]['user']; ?>';
    var userPsw='<?php echo decodeKey($userMoodle[0]['psw'],$sso_salt); ?>';
    var msg_span='';
    if (userName!='') {
        $('#ac_loading').attr("style","");
        getCurLogin();
    }

function getCurLogin()   //проверить текущий статус авторизации
{        
        $('#ac_loading').attr("style","");
        $.ajax({        
            url: moodle_path+"/login/index.php",        
            success: function(html){
                var err=false;  //флаг ошибки
                var msg=''; //текст ошибки          
                // статус авторизации с попыткой повторного логина, при необходимости
                getUserStatus(err,msg,html,true,false); 
            }
        });      
}
</script>
        <?php
        }        
}
?>