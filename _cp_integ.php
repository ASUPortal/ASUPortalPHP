<?php
//
//интеграция с Coppermine Photogallery через ajax-авторизацию, подробнее в sso_moodle.php
function getIntegStatus($slave_system_id)   // вывод статуса интеграции приложения
{
        global $sql_base;
        //поиск связки пользователя для автоматической авторизации в Mantis
        $master_user_id=intval($_SESSION['id']);        
        
        $userSlaveSystem=getRowSqlVar('select sut.slave_user_hash as user,sut.slave_psw_hash as psw
                                 from `'.$sql_base.'`.sso_user_table sut 
                                    inner join `'.$sql_base.'`.sso_systems ss on ss.id=sut.slave_system_id 
                                 where sut.master_user_id='.$master_user_id.'
                                        and sut.slave_system_id='.$slave_system_id.'
                                 limit 0,1');		

        if (is_array($userSlaveSystem) && trim($userSlaveSystem[0]['user']!='')) {
        // массив параметров интеграции системы, путь, страницы ...
        $SlaveSystem=getRowSqlVar('select * from `'.$sql_base.'`.sso_systems where id='.$slave_system_id);

        ?>
<p class=text><img src="<?php echo $SlaveSystem[0]['logo_img']; ?>" height=40 alt="<?php echo $SlaveSystem[0]['name']; ?>" title="<?php echo $SlaveSystem[0]['comment']; ?>" style="padding-top:-10px; margin-top:-10px;">
    <span id="ac_loading<?php echo $slave_system_id; ?>" class="cascadeSelect_loading" style="display:none;";> </span>
    <span id="msg<?php echo $slave_system_id; ?>"></span>
    <a href="sso_cp.php?slsys_id=<?php echo $slave_system_id; ?>" style="padding-left:40px;" class=success>настроить...</a></p>        

<script language="JavaScript">

    SlaveSystemPath="<?php echo $SlaveSystem[0]['path']; ?>";
    SlaveSystemRespTemp="<?php echo $SlaveSystem[0]['response_templ']; ?>";  // ссылка_выхода, имя_пользователя
    SlaveSystemLoginPH="<?php echo $SlaveSystem[0]['login_pg_path']; ?>";
    SlaveSystemLogoutPH="<?php echo $SlaveSystem[0]['logout_pg_path']; ?>";
    SlaveSystemId="<?php echo $slave_system_id; ?>";
    SlaveSystemProfile="<?php echo $SlaveSystem[0]['profile_link']; ?>";  // ссылка на профиль пользователя
    
    userName='<?php echo $userSlaveSystem[0]['user']; ?>';
    userPsw='<?php echo decodeKey($userSlaveSystem[0]['psw'],$SlaveSystem[0]['psw_salt']); ?>';
    msg_span='';
        
    if (userName!='') {
        $('#ac_loading'+SlaveSystemId).attr("style","");
        getCurLogin(false);
    }
    
</script>
        <?php
        }   
}
// библиотека шифрования
include_once 'funcs_php_crypt.php';
?>
<script language="JavaScript" src="scripts/cp_auth.js"></script>
<script language="JavaScript">
    var SlaveSystemPath="";
    var SlaveSystemRespTemp="";  // ссылка_выхода, имя_пользователя
    var SlaveSystemLoginPH="";
    var SlaveSystemLogoutPH="";
    var SlaveSystemId="";
    var SlaveSystemProfile="";  // ссылка на профиль пользователя
    
    var userName='';
    var userPsw='';
    var msg_span='';
    
    $.ajaxSetup({          
      async: false,
      crossDomain: true
    }); 
</script>    
<?php
// отражать только для авторизованных пользователей
if (isset($_SESSION['id']) && intval($_SESSION['id'])>0)    
{
    // список активных интеграторов (шлюзов интеграции)
    $query='select id from `'.$sql_base.'`.sso_systems where enable_status=1';
    $res=mysql_query($query);
    while ($a=mysql_fetch_assoc($res))
        getIntegStatus(intval($a['id'])); 

     // 1 - moodle, 2 - Mantis, 3- CP    

}
    
?>
<script language="JavaScript">
    
    $.ajaxSetup({          
      async: true,
      crossDomain: true
    });
</script> 