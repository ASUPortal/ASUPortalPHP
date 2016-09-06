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
	        /**
	         * Функция смены статуса
	         *
	         * @param value
	         */
	        function changeStatus(item) {
	        	var container = item.target || item.srcElement;
                var id = jQuery(container).attr("asu-id");
                var action = jQuery(container).attr("asu-action");
                jQuery.ajax({
                    url: web_root + "_modules/_corriculum/workplans.php",
                    beforeSend: function(){
                        jQuery(container).html('<i class="icon-signal"></i>');
                    },
                    cache: false,
                    context: item,
                    data: {
                        action: action,
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
	        }
            jQuery(document).ready(function(){
                /**
                 * Обрабатываем смену статуса комментария к файлу
                 */
                jQuery.each(jQuery(".changeStatusComment"), function(key, value){
                    // раскрашиваем ячейку статуса комментария к файлу
                    colorizeCell(value);
                });
                jQuery(".changeStatusComment").on("click", function(item){
                	// изменяем статус комментария к файлу
                    changeStatus(item);
                });
                
                /**
                 * Обрабатываем смену статуса на портале
                 */
                jQuery.each(jQuery(".changeStatusOnPortal"), function(key, value){
                    // раскрашиваем ячейку статуса на портале
                    colorizeCell(value);
                });
                jQuery(".changeStatusOnPortal").on("click", function(item){
                	// изменяем статус на портале
                    changeStatus(item);
                });

                /**
                 * Обрабатываем смену статуса рабочей программы (библиотека)
                 */
                jQuery.each(jQuery(".changeStatusWorkPlanLibrary"), function(key, value){
                    // раскрашиваем ячейку библиотеки
                    colorizeCell(value);
                });
                jQuery(".changeStatusWorkPlanLibrary").on("click", function(item){
                	// изменяем статус библиотеки
                    changeStatus(item);
                });

                /**
                 * Обрабатываем смену статуса рабочей программы (преподаватель)
                 */
                jQuery.each(jQuery(".changeStatusWorkPlanLecturer"), function(key, value){
                    // раскрашиваем ячейку преподавателя
                    colorizeCell(value);
                });
                jQuery(".changeStatusWorkPlanLecturer").on("click", function(item){
                	// изменяем статус преподавателя
                    changeStatus(item);
                });

                /**
                 * Обрабатываем смену статуса рабочей программы (зав. каф.)
                 */
                jQuery.each(jQuery(".changeStatusWorkPlanHeadOfDepartment"), function(key, value){
                    // раскрашиваем ячейку зав. каф.
                    colorizeCell(value);
                });
                jQuery(".changeStatusWorkPlanHeadOfDepartment").on("click", function(item){
                	// изменяем статус заведующего кафедрой
                    changeStatus(item);
                });

                /**
                 * Обрабатываем смену статуса рабочей программы (НМС)
                 */
                jQuery.each(jQuery(".changeStatusWorkPlanNMS"), function(key, value){
                    // раскрашиваем ячейку НМС
                    colorizeCell(value);
                });
                jQuery(".changeStatusWorkPlanNMS").on("click", function(item){
                	// изменяем статус НМС
                    changeStatus(item);
                });

                /**
                 * Обрабатываем смену статуса рабочей программы (декан)
                 */
                jQuery.each(jQuery(".changeStatusWorkPlanDean"), function(key, value){
                    // раскрашиваем ячейку декана
                    colorizeCell(value);
                });
                jQuery(".changeStatusWorkPlanDean").on("click", function(item){
                	// изменяем статус декана
                    changeStatus(item);
                });

                /**
                 * Обрабатываем смену статуса рабочей программы (проректор)
                 */
                jQuery.each(jQuery(".changeStatusWorkPlanProrektor"), function(key, value){
                    // раскрашиваем ячейку проректора
                    colorizeCell(value);
                });
                jQuery(".changeStatusWorkPlanProrektor").on("click", function(item){
                	// изменяем статус проректора
                    changeStatus(item);
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
            
            .changeStatusOnPortal {
                cursor: pointer;
            }
            .changeStatusOnPortal:hover {
                text-decoration: underline;
            }
            
            .changeStatusWorkPlanLibrary {
                cursor: pointer;
            }
            .changeStatusWorkPlanLibrary:hover {
                text-decoration: underline;
            }
            
            .changeStatusWorkPlanLecturer {
                cursor: pointer;
            }
            .changeStatusWorkPlanLecturer:hover {
                text-decoration: underline;
            }
            
            .changeStatusWorkPlanHeadOfDepartment {
                cursor: pointer;
            }
            .changeStatusWorkPlanHeadOfDepartment:hover {
                text-decoration: underline;
            }
            
            .changeStatusWorkPlanNMS {
                cursor: pointer;
            }
            .changeStatusWorkPlanNMS:hover {
                text-decoration: underline;
            }
            
            .changeStatusWorkPlanDean {
                cursor: pointer;
            }
            .changeStatusWorkPlanDean:hover {
                text-decoration: underline;
            }
            
            .changeStatusWorkPlanProrektor {
                cursor: pointer;
            }
            .changeStatusWorkPlanProrektor:hover {
                text-decoration: underline;
            }
        </style>