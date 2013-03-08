//авторизовал ли пользователь в Moodle 
function getUserStatus(err,msg,html,relogin,exitshow)    
{    
    var user_text;
    var user_name='';
    if (exitshow==null || exitshow!=true) exitshow=false;
	if (relogin==null || relogin!=true) relogin=false;
    
    re = new RegExp("<div class=\"logininfo\">.*view.php?(.*)\">(.*)</a>.*sesskey=(.*)\">.*</div>");
    user_text = re.exec(html);
        
    if (user_text!=null) {        
        user_name=user_text[2];
	user_q_string=user_text[1];
    }
    if (user_name!='')    {  
    err=false;   
	msg='Вы успешно авторизованы как <a href="'+moodle_path+'/user/view.php'+
	    user_q_string+'">'+user_name+'</a>.';      
      if (exitshow==true)
	msg+='<a  href="javascript:getExitLogin(\''+user_text[3]+'\');">Выход</a>';
      print_msg(err,msg);
      // запоминаем ID пользователя moodle      
	user_id = user_q_string.replace(/.*id=(\d+).*/i,'$1');
       $("#userid").val(user_id);
    }
    else {
      
      // попытка повторного логина
      if (relogin==true && typeof(userName)!='undefined')
	autologin(userName,userPsw,null,exitshow); 
      else 
      {
      err=true; msg='пользователь в Moodle не авторизован;';
      
      print_msg(err,msg);}
    }
    $('#ac_loading').attr("style","display:none;");
    return !err;  
}   
//вывод сообщения
function print_msg(err,msg,mode)   
{
    if (err===true) {
	$('#msg').removeClass('success').addClass('warning');
	$('#moodleForm').show();
    }
    else {
	$('#msg').removeClass('warning').addClass('success');
	$('#moodleForm').hide();
    }	
    
    if (mode=='add') $('#msg').append(msg);
    else $('#msg').html(msg);
}
//авторизация по имени и паролю
function autologin(userName,userPsw,func_succ_name,exitshow)
{    
    var err=false;  //флаг ошибки
    var msg=''; //текст ошибки
    if (func_succ_name==null) func_succ_name='';	// функция при успехе
    if (exitshow==null) exitshow=true;
        
    if (userName=='') {
        err=true; msg='не указано имя пользователя;';
        print_msg(err,msg);        
    }
    else {
    
    $('#ac_loading').attr("style","");
    $.ajax({
        type: "POST",
        url: moodle_path+"/login/index.php",
        data: "username="+userName+"&password="+userPsw,
        success: function(html){
            var err=false;  //флаг ошибки
            var msg=''; //текст ошибки                    			
            if (getUserStatus(err,msg,html,false,exitshow) && func_succ_name!='')
		eval(func_succ_name);            
        }
    });
    }
    $('#ac_loading').attr("style","display:none;");
}