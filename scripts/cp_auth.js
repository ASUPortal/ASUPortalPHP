//сохранить авторизацю в БД
function saveLogin(userName,userPsw,url_path)
{
    
    $('#ac_loading'+SlaveSystemId).attr("style","");
    if (userName!='') {        
        $.ajax({
            type: "POST",
            url: url_path,
            data: "username="+userName+"&password="+userPsw,
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
    $('#ac_loading'+SlaveSystemId).attr("style","display:none;");
}
 //проверить текущий статус авторизации
function getCurLogin(exit_show)  
{   
    if (typeof(exit_show)=="undefined") exit_show=false;
	
	$('#ac_loading'+SlaveSystemId).attr("style","");
            
        $.ajax({        
            type: "POST",
			url: SlaveSystemPath+SlaveSystemLoginPH,        
            success: function(html){
                var err=false;  //флаг ошибки
                var msg=''; //текст ошибки                      
                // +попытка релогина при try_relogin=true                               
                getUserStatus(err,msg,html,true,exit_show); 
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {			   
               print_msg(true,'интеграция не возможна ('+
                    (errorThrown!=null?errorThrown:textStatus)+'), обратитесь к Администратору ...'); 
               $('#ac_loading'+SlaveSystemId).attr("style","display:none;");
            }
        }); 

}   
//закрыть авторизацию (выход)
function getExitLogin(query_str)   
{        
	$('#ac_loading'+SlaveSystemId).attr("style","");
        $.ajax({        
            url: SlaveSystemPath+SlaveSystemLogoutPH+query_str,        
            success: function(html){
                var err=false;  //флаг ошибки
                var msg=''; //текст ошибки          
                getUserStatus(err,msg,html);
                    // удалить рег.данные из хранилища       
                    $.ajax({        
                        url: "sso_cp.php?exit=1&slsys_id="+SlaveSystemId,        
                        success: function(html){
                            var err=false;  //флаг ошибки
                            var msg=html; //текст ошибки                                      
                            print_msg(true,msg,'add');                
                        }
                    });                
            }
        });                      
}

//авторизовал ли пользователь в Сервисе 
function getUserStatus(err,msg,html,relogin,exitshow)    
{    
    var user_text;
    var user_name='';
    if (exitshow==null || exitshow!=true) exitshow=false;
	if (relogin==null || relogin!=true) relogin=false;
    
    // html-шаблон поиска в ответе имени пользователя
    re = new RegExp(SlaveSystemRespTemp);
    user_text = re.exec(html);
        
    if (user_text!=null) {        
        user_name=user_text[2];
	user_q_string=SlaveSystemProfile;//user_text[1];
	
    }
    
    if (user_name!='')    {  
    err=false;   
	msg='Вы успешно авторизованы как <a href="'+SlaveSystemPath+''+
	    user_q_string+'">'+user_name+'</a>.';      
      
      if (exitshow==true)
	msg+='<a style="margin-left:20px;" href="javascript:getExitLogin(\''+user_text[1]+'\');">Выход</a>';
      print_msg(err,msg);
    }
    else {
      
      // попытка повторного логина
      if (relogin==true && typeof(userName)!='undefined')
	autologin(userName,userPsw,null,exitshow); 
      else 
      {
      err=true; msg='пользователь в сервисе не авторизован;';
      
      print_msg(err,msg);}
    }
    $('#ac_loading'+SlaveSystemId).attr("style","display:none;");
    return !err;  
}   
//вывод сообщения
function print_msg(err,msg,mode)   
{
    if (err===true) {
	$('#msg'+SlaveSystemId).removeClass('success').addClass('warning');
	$('#loginForm').show();
    }
    else {
	$('#msg'+SlaveSystemId).removeClass('warning').addClass('success');
	$('#loginForm').hide();
    }	
    
    if (mode=='add') $('#msg'+SlaveSystemId).append(msg);
    else $('#msg'+SlaveSystemId).html(msg);
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
    
    $('#ac_loading'+SlaveSystemId).attr("style","");
    $.ajax({
        type: "POST",
        url: SlaveSystemPath+SlaveSystemLoginPH,
        data: "username="+userName+"&password="+userPsw+"&submitted=OK",
        success: function(html){
            var err=false;  //флаг ошибки
            var msg=''; //текст ошибки                    			
            if (getUserStatus(err,msg,html,false,exitshow) && func_succ_name!='')
		eval(func_succ_name);            
        }
    });
    }
    $('#ac_loading'+SlaveSystemId).attr("style","display:none;");
}