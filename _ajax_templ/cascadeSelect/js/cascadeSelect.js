//функции по фильтрации зависимого списка

(function($){
  // очищаем select
  $.fn.clearSelect = function() {
	  return this.each(function(){
		  if(this.tagName=='SELECT') {
		      this.options.length = 0;
		      $(this).attr('disabled','disabled');
		  }
	  });
  }
  // заполняем select
  $.fn.fillSelect = function(dataArray) {
	  return this.clearSelect().each(function(){
		  if(this.tagName=='SELECT') {
			  var currentSelect = this;
			  $.each(dataArray,function(index,data){
				  var option = new Option(data.text,data.value,data.selected,data.selected);
				  if($.support.cssFloat) {
					  currentSelect.add(option,null);
				  } else {
					  currentSelect.add(option);
				  }
			  });
		  }
	  });
  }
})(jQuery);

  // выбор значения в главном списке 
  function adjustList2(listMainId,listSlaveId,typeQuery,MainIsNull,selectValue){
  	//alert(selectValue);
	var list1Value = $('#'+listMainId+'').val();
  	var tmpSelect = $('#'+listSlaveId+'');  	
  	
	  if(list1Value== 0 && MainIsNull!='allowMainIsNull') {
  		tmpSelect.attr('disabled','disabled');
  		tmpSelect.clearSelect();
  	} else {	//запрос на фильтрацию зависимого списка, в typeQ необходимо передавать заранее описанный тип запроса
  		//$('#ac_loading').addClass("cascadeSelect_loading");
  		$('#ac_loading').attr("style","");
  		//$('#ac_loading').addClass("cascadeSelect_loading");
		  $.getJSON('_ajax_templ/cascadeSelect/cascadeSelectQuery.php',{list1:list1Value,typeQ:typeQuery,select_id:selectValue,hidejq:''},
		  	function(data) { tmpSelect.fillSelect(data).attr('disabled',''); });  
			  
			   $('#'+listSlaveId+'').ajaxComplete(function(event,request, settings){
   					//$(this).append("<li>Request Complete.</li>");
   					$('#ac_loading').attr("style","display:none;");
					   //$('#ac_loading').removeClass("cascadeSelect_loading");
   					//alert('end');
 				});		
  	}  	
  };