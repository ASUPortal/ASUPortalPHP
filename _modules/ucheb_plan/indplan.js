function confirm_delete() 
{ 
   if(confirm("Вы уверены что хотите удалить?")) 
     { 
        document.myForm.submit(); 
     } 
     else {
	 document.getElementById('delete').name=false;
	}
	
}

function del_confirm_act(id,num)
{
	 if(confirm('Удалить запись: '+num+' ?')) 
	 	{window.location.href=window.location.href+'&id='+id+'&delete';} 
}




function prosmotr_check()
       {
        if (select1.value == "0" || select2.value == "0") 
{

window.alert('Необходимо выбрать преподавателя и учебный год');

}
else
{
var t2=document.getElementById('select2').value;
var t1=document.getElementById('select1').value;
document.getElementById('ssilka1').href="prosmotr.php?id_kadri="+t1+"&id_year="+t2;
}
      
	}


function rab_prep_check()
       {
        if (select2.value == "0") 
{

window.alert('Необходимо выбрать учебный год');

}
else
{
var t2=document.getElementById('select2').value;
document.getElementById('ssilka3').href="rab_prep.php?id_year="+t2;;
}
      
	}
