	<script>
	    function removeFilter() {
	        var action = "?action=index";
	        window.location.href = action;
	    }
		jQuery(document).ready(function(){
			jQuery("#isArchive").change(function(){
				window.location.href=web_root + "_modules/_corriculum/workplans.php?isArchive=" + (jQuery(this).is(":checked") ? "1":"0");
			});
			{if !$isApprove}
				{if !is_null($currentPerson)}
					jQuery("#corriculum_selector").change(function(){
		                window.location.href=web_root + "_modules/_corriculum/workplans.php?filter=corriculum.id:" + jQuery(this).val() + "_person.id:{$currentPerson}";
		            });
				{else}
					jQuery("#corriculum_selector").change(function(){
		                window.location.href=web_root + "_modules/_corriculum/workplans.php?filter=corriculum.id:" + jQuery(this).val();
		            });
		    	{/if}
		    	{if !is_null($currentCorriculum)}
			    	jQuery("#person_selector").change(function(){
		                window.location.href=web_root + "_modules/_corriculum/workplans.php?filter=person.id:" + jQuery(this).val() + "_corriculum.id:{$currentCorriculum}";
		            });
			    {else}
				    jQuery("#person_selector").change(function(){
		                window.location.href=web_root + "_modules/_corriculum/workplans.php?filter=person.id:" + jQuery(this).val();
		            });
		    	{/if}
		    {else}
				{if !is_null($currentPerson)}
					jQuery("#corriculum_selector").change(function(){
		                window.location.href=web_root + "_modules/_corriculum/workplans.php?filter=corriculum.id:" + jQuery(this).val() + "_person.id:{$currentPerson}&isApprove=1";
		            });
				{else}
					jQuery("#corriculum_selector").change(function(){
		                window.location.href=web_root + "_modules/_corriculum/workplans.php?filter=corriculum.id:" + jQuery(this).val() + "&isApprove=1";
		            });
		    	{/if}
		    	{if !is_null($currentCorriculum)}
			    	jQuery("#person_selector").change(function(){
		                window.location.href=web_root + "_modules/_corriculum/workplans.php?filter=person.id:" + jQuery(this).val() + "_corriculum.id:{$currentCorriculum}&isApprove=1";
		            });
			    {else}
				    jQuery("#person_selector").change(function(){
		                window.location.href=web_root + "_modules/_corriculum/workplans.php?filter=person.id:" + jQuery(this).val() + "&isApprove=1";
		            });
		    	{/if}
		    {/if}
		});
	</script>
	<form action="workplans.php" method="post" enctype="multipart/form-data" class="form-horizontal">
		{if (CSession::getCurrentUser()->getLevelForCurrentTask() == {$ACCESS_LEVEL_READ_ALL} or CSession::getCurrentUser()->getLevelForCurrentTask() == {$ACCESS_LEVEL_WRITE_ALL})}	
			<table border="0" width="100%" class="tableBlank">
				<tr>
					<td valign="top" width="100%">
						<div class="form-horizontal">
		        			<div class="control-group">
		            			<label class="control-label" for="corriculum.id">Учебный план</label>
		            			<div class="controls">
		                			{CHtml::dropDownList("corriculum.id", $workplanCorriculums, $currentCorriculum, "corriculum_selector", "span12")}
		            			</div>
		        			</div>
		    			</div>
					</td>
				</tr>
			</table>
			<table border="0" width="100%" class="tableBlank">
				<tr>
					<td valign="top" width="60%">
						<div class="form-horizontal">
		        			<div class="control-group">
		            			<label class="control-label" for="person.id">Автор</label>
		            			<div class="controls">
		                			{CHtml::dropDownList("person.id", $workplanAuthors, $currentPerson, "person_selector", "span12")}
		            			</div>
		        			</div>
		    			</div>
					</td>
		      		<td valign="top">
		      			{CHtml::hiddenField("action", "index")}
		      			{CHtml::textField("textSearch", "", "", "span12", "placeholder=Поиск")}
					</td>
				</tr>
			</table>
		{else}
			<table border="0" width="100%" class="tableBlank">
				<tr>
					<td valign="top" width="60%">
						<div class="form-horizontal">
		        			<div class="control-group">
		            			<label class="control-label" for="corriculum.id">Учебный план</label>
		            			<div class="controls">
		                			{CHtml::dropDownList("corriculum.id", $workplanCorriculums, $currentCorriculum, "corriculum_selector", "span12")}
		            			</div>
		        			</div>
		    			</div>
					</td>
		      		<td valign="top">
		      			{CHtml::hiddenField("action", "index")}
		      			{CHtml::textField("textSearch", "", "", "span12", "placeholder=Поиск")}
					</td>
				</tr>
			</table>
		{/if}
			<table border="0" width="100%" class="tableBlank">
				<tr>
					<td>			
						<div class="form-horizontal">
							<div class="control-group">
								<label class="control-label" for="isArchive">В архиве</label>
								<div class="controls">
									{CHtml::checkBox("isArchive", "1", $isArchive, "isArchive")}
								</div>
							</div>
						</div>
		    		</td>
		    		<td valign="top">
						<p align="center">
							<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter(); return false; " title="очистить фильтры"/></span>
						</p>
					</td>
				</tr>
	    	</table>
	</form>
	
    	<script>
	        /**
	         * Функция раскрашивания ячейки
	         *
	         * @param value
	         */
	        function colorizeCell(value) {
	            var color = jQuery(value).attr("asu-color");
	            var cell = jQuery(value).parents("td");
	            jQuery(cell).css("background-color", color);
	        }
            jQuery(document).ready(function(){
                jQuery.each(jQuery(".changeStatusComment"), function(key, value){
                    // раскрашиваем ячейку
                    colorizeCell(value);
                });
                /**
                 * Обрабатываем смену статуса комментария к файлу
                 */
                jQuery(".changeStatusComment").on("click", function(item){
                    var container = item.target || item.srcElement;
                    var id = jQuery(container).attr("asu-id");
                    jQuery.ajax({
                        url: web_root + "_modules/_corriculum/workplans.php",
                        beforeSend: function(){
                            jQuery(container).html('<i class="icon-signal"></i>');
                        },
                        cache: false,
                        context: item,
                        data: {
                            action: "updateCommentFile",
                            id: id
                        },
                        dataType: "json",
                        method: "GET",
                        success: function(data){
                            jQuery(container).attr("asu-color", data.color);
                            jQuery(container).html(data.title);
                            colorizeCell(container);
                        }
                    });
                });
                jQuery.each(jQuery(".changeStatusWorkPlan"), function(key, value){
                    // раскрашиваем ячейку
                    colorizeCell(value);
                });
                /**
                 * Обрабатываем смену статуса рабочей программы
                 */
                jQuery(".changeStatusWorkPlan").on("click", function(item){
                    var container = item.target || item.srcElement;
                    var id = jQuery(container).attr("asu-id");
                    jQuery.ajax({
                        url: web_root + "_modules/_corriculum/workplans.php",
                        beforeSend: function(){
                            jQuery(container).html('<i class="icon-signal"></i>');
                        },
                        cache: false,
                        context: item,
                        data: {
                            action: "updateStatusWorkPlan",
                            id: id
                        },
                        dataType: "json",
                        method: "GET",
                        success: function(data){
                            jQuery(container).attr("asu-color", data.color);
                            jQuery(container).html(data.title);
                            colorizeCell(container);
                        }
                    });
                });
                jQuery.each(jQuery(".changeStatusOnPortal"), function(key, value){
                    // раскрашиваем ячейку
                    colorizeCell(value);
                });
                /**
                 * Обрабатываем смену статуса на портале
                 */
                jQuery(".changeStatusOnPortal").on("click", function(item){
                    var container = item.target || item.srcElement;
                    var id = jQuery(container).attr("asu-id");
                    jQuery.ajax({
                        url: web_root + "_modules/_corriculum/workplans.php",
                        beforeSend: function(){
                            jQuery(container).html('<i class="icon-signal"></i>');
                        },
                        cache: false,
                        context: item,
                        data: {
                            action: "updateStatusOnPortal",
                            id: id
                        },
                        dataType: "json",
                        method: "GET",
                        success: function(data){
                            jQuery(container).attr("asu-color", data.color);
                            jQuery(container).html(data.title);
                            colorizeCell(container);
                        }
                    });
                });
            });
        </script>
        <style>
            .changeStatusComment {
                cursor: pointer;
            }
            .changeStatusComment:hover {
                text-decoration: underline;
            }
            .changeStatusWorkPlan {
                cursor: pointer;
            }
            .changeStatusWorkPlan:hover {
                text-decoration: underline;
            }
            .changeStatusOnPortal {
                cursor: pointer;
            }
            .changeStatusOnPortal:hover {
                text-decoration: underline;
            }
        </style>