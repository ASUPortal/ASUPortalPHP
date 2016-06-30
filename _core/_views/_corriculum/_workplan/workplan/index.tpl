{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Рабочие программы</h2>
    {CHtml::helpForCurrentPage()}
    
	<script>
	    function removeFilter() {
	        var action = "?action=index";
	        window.location.href = action;
	    }
		jQuery(document).ready(function(){
			jQuery("#isArchive").change(function(){
				window.location.href=web_root + "_modules/_corriculum/workplans.php?isArchive=" + (jQuery(this).is(":checked") ? "1":"0");
			});
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
            jQuery(document).ready(function(){
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
                            jQuery(container).html(data.title);
                        }
                    });
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
                            jQuery(container).html(data.title);
                        }
                    });
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
                            jQuery(container).html(data.title);
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
        
    <form action="workplans.php" method="post" id="MainView">
    {if $plans->getCount() == 0}
        <div class="alert">
            Нет рабочих программ для отображения
        </div>
	{else}

	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th></th>
	            <th>{CHtml::activeViewGroupSelect("id", $plans->getFirstItem(), true)}</th>
	            <th>№</th>
				<th></th>
	            <th>{CHtml::tableOrder("title_display", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("discipline.name", $plans->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("corriculum.title", $plans->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("year", $plans->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("term.name", $plans->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("person.fio", $plans->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("title", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("comment_file", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("status_workplan", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("status_on_portal", $plans->getFirstItem())}</th>
	        </tr>
	        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $plans->getItems() as $plan}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить рабочую программу дисциплины {$plan->discipline}')) { location.href='?action=delete&id={$plan->id}'; }; return false;"></a></td>
	            <td>{CHtml::activeViewGroupSelect("id", $plan, false, true)}</td>
	            <td>{counter}</td>
	            <td><a href="?action=edit&id={$plan->getId()}" class="icon-pencil"></a></td>
	            <td>{$plan->title_display}</td>
	            <td>{$plan->discipline}</td>
	            <td>
	            	{if !is_null($plan->corriculumDiscipline)}
		            	{if !is_null($plan->corriculumDiscipline->cycle)}
			            	{if !is_null($plan->corriculumDiscipline->cycle->corriculum)}
			            		<a href="{$web_root}_modules/_corriculum/?action=view&id={$plan->corriculumDiscipline->cycle->corriculum->getId()}">{$plan->corriculumDiscipline->cycle->corriculum->title}</a>
			            	{/if}
		            	{/if}
	            	{/if}
	            </td>
	            <td>{$plan->year}</td>
	            <td>{", "|join:$plan->profiles->getItems()}</td>
				<td>{", "|join:$plan->authors->getItems()}</td>
				<td>{$plan->title}</td>
				<td>
                    <span>
                        <span class="changeStatusComment" asu-id="{$plan->getId()}">
                            {if $plan->comment_file == 0 or is_null($plan->commentFile)}
                                Нет комментария
                            {else}
                                {$plan->commentFile->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	            <td>
                    <span>
                        <span class="changeStatusWorkPlan" asu-id="{$plan->getId()}">
                            {if $plan->status_workplan == 0 or is_null($plan->statusWorkplan)}
                                Нет комментария
                            {else}
                                {$plan->statusWorkplan->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	            <td>
                    <span>
                        <span class="changeStatusOnPortal" asu-id="{$plan->getId()}">
                            {if $plan->status_on_portal == 0 or is_null($plan->statusOnPortal)}
                                Нет комментария
                            {else}
                                {$plan->statusOnPortal->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	        </tr>
	        {/foreach}
	    </table>
	    </form>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
{include file="_corriculum/_workplan/workplan/index.right.tpl"}
{/block}
