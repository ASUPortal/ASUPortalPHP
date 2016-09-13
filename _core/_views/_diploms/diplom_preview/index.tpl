{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Предзащита ВКР - студенты</h2>

    {CHtml::helpForCurrentPage()}
    
	<script>
    /**
     * Очистка указанного фильтра
     * @param type
     */
    function removeFilter(type) {
        var filters = new Object();
        {if !is_null($currentCommission)}
            filters.comm_id = {$currentCommission};
        {/if}
        {if !is_null($currentGroup)}
            filters.group_id = {$currentGroup};
        {/if}
        var action = "?action=index&filter=";
        var actions = new Array();
        jQuery.each(filters, function(key, value){
            if (key !== type) {
                actions[actions.length] = key + ":" + value;
            }
        });
        action = action + actions.join("_");
        window.location.href = action;
    }
    jQuery(document).ready(function(){
    	var filters = new Object();
    	{if !is_null($currentCommission)}
    		filters.commission = {$currentCommission};
    	{/if}
    	{if !is_null($currentGroup)}
    		filters.group = {$currentGroup};
    	{/if}
    	var isArchive = false;
    	{if $isArchive}
    		isArchive = true;
    	{/if}
    	function updateFilter() {
    		var query = new Array();
    		var filter = new Array();
    		$.each(filters, function(key, value){
    			if (value != 0) {
        			query[query.length] = key + "=" + value;
        			filter[filter.length] = key + ":" + value;	
    			}
    		});
    		if (isArchive) {
    			query[query.length] = "isArchive=1";
    		}
    		query[query.length] = "filter=" + filter.join("~");
    		query[query.length] = "action=index";
    		window.location.href = "preview.php?" + query.join("&");
    	}
    	$("#comm_id").change(function(){
    		filters.commission = $(this).val();
    		updateFilter();
    	});
    	$("#show_groups").click(function(){
    		filters.group = $("#multiple").val();
    		updateFilter();
    	});
    	jQuery("#selectAll").change(function(){
    		var items = jQuery("input[name='selectedDoc[]']")
            for (var i = 0; i < items.length; i++) {
                items[i].checked = this.checked;
            }
        });
    	/**
         * Это старый код адаптированный под Bootstrap
         * Он не страшный, он оставлен таким, какой он есть, чтобы бэкэнд
         * не переписывать
         **/
        var searchResults = new Object();
        jQuery("#search").typeahead({
            source: function (query, process) {
                return jQuery.ajax({
                    url: "#",
                    type: "get",
                    cache: false,
                    dataType: "json",
                    data: {
                        "query": query,
                        "action": "search"
                    },
                    beforeSend: function(){
                        /**
                         * Показываем индикатор активности
                         */
                        jQuery("#search").css({
                            "background-image": 'url({$web_root}images/ajax-loader.gif)',
                            "background-repeat": "no-repeat",
                            "background-position": "95% center"
                        });
                    },
                    success: function(data){
                        var lookup = new Array();
                        searchResults = new Object();
                        for (var i = 0; i < data.length; i++) {
                            lookup.push(data[i].label);
                            searchResults[data[i].label] = data[i];
                        }
                        process(lookup);
                        jQuery("#search").css("background-image", "none");
                    }
                });
            },
            updater: function(item){
                var selected = searchResults[item];
                if (selected.type == 1) {
                    // выбран студент
                	window.location.href = "?action=index&isArchive=1&filter=student:" + selected.object_id;
                } else if(selected.type == 2) {
                    // выбран тема ВКР
                    window.location.href = "?action=index&isArchive=1&filter=student:" + selected.object_id;
                } else if(selected.type == 3) {
                    // выбран рецензент
                	window.location.href = "?action=index&isArchive=1&filter=student:" + selected.object_id;
                } else if(selected.type == 4) {
                    // выбран рецензент по id
                	window.location.href = "?action=index&isArchive=1&filter=student:" + selected.object_id;
                } else if(selected.type == 5) {
                    // выбран комментарий
                	window.location.href = "?action=index&isArchive=1&filter=comment:" + selected.object_id;
                }
            }
        });
    });
	</script>
    
    <table border="0" width="100%" class="tableBlank">
		<tr>
			<td valign="top">
				<div class="form-horizontal">
        			<div class="control-group">
            			<label class="control-label" for="person">Комиссия</label>
            			<div class="controls">
                			{CHtml::dropDownList("commission", $commissions, $currentCommission, "comm_id", "span12")}
                			{if !is_null($currentCommission)}
                    			<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('commission'); return false; "/></span>
                			{/if}  
            			</div>
        			</div>
    			</div>
			</td>
      		<td valign="top">
				<p>
					<input type="text" id="search" style="width: 96%; " placeholder="Поиск">
				</p>
			</td>
		</tr>
	</table>
	<form action="index.php">
	<table border="0" width="100%" class="tableBlank">
		<tr>
			<td>			
				<div class="form-horizontal">
        			<div class="control-group">
            			<label class="control-label" for="group">Группа</label>
            			<div class="controls">
            				<p>
            					<select id="multiple" multiple>
            					{foreach $studentGroups as $key=>$value}
									<option value={$key}>{$value}</option>
								{/foreach}
								</select>
							</p>
            			</div>
        			</div>
    			</div>
    		<td valign="top" width="50%">
    			<div id="show_groups"><input name="" type="button" class="btn" value="Показать"></div>
    		</td>
    		</td>
    		<td valign="top" width="5%">
				<p align="center">
					<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter(); return false; " title="очистить фильтры"/></span>
				</p>
			</td>
		</tr>
    </table>
    </form>

{if ($previews->getCount() == 0)}
	Нет объектов для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th></th>
            <th><input type="checkbox" id="selectAll"></th>
            <th>№</th>
			<th>{CHtml::tableOrder("student.fio", $previews->getFirstItem(), true)}</th>
			<th>{CHtml::tableOrder("st_group.name", $previews->getFirstItem(), true)}</th>
			<th>{CHtml::tableOrder("diplom.dipl_name", $previews->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("diplom_percent", $previews->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("another_view", $previews->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("person.fio", $previews->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("date_preview", $previews->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("comm.name", $previews->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("comment", $previews->getFirstItem(), true)}</th>
        </tr>
        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $previews->getItems() as $preview}
        <tr>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить предзащиту студента {$preview->student->getName()}')) { location.href='?action=deletePreview&id={$preview->id}'; }; return false;"></a></td>
			<td><a href="preview.php?action=editPreview&id={$preview->getId()}" class="icon-pencil" title="правка"></a></td>
            <td>
				<input type="checkbox" value="{$preview->getId()}" name="selectedDoc[]">
			</td>
            <td>{counter}</td>
            <td>
				{if !is_null($preview->student)}
					{if !is_null($preview->student->getGroup())}
						<a href="{$web_root}_modules/_students/index.php?action=edit&id={$preview->student->getId()}">{$preview->student->getName()}</a>
					{/if}
				{/if}
			</td>
			<td>
				{if !is_null($preview->student)}
					{if !is_null($preview->student->getGroup())}
						{$preview->student->getGroup()->getName()}
					{/if}
				{/if}
			</td>
            <td>
                {if !is_null($preview->diplom)}
                	<a href="{$web_root}_modules/_diploms/index.php?action=edit&id={CStaffManager::getDiplomByStudent($preview->student_id)->id}">{$preview->diplom->dipl_name}</a>
				{elseif $preview->diplom_id==0}
					<a href="{$web_root}_modules/_diploms/index.php?action=edit&id={CStaffManager::getDiplomByStudent($preview->student_id)->id}">{CStaffManager::getDiplomByStudent($preview->student_id)->dipl_name}</a>
                {/if}
            </td>
            <td>
                {$preview->diplom_percent}
            </td>
            <td>
            	{if $preview->another_view==0}
            		Нет
            	{else}
            		Да
            	{/if}
            </td>
			<td>
                {if !is_null($preview->diplom)}
                	{if $preview->diplom->recenz_id==0}
                		{$preview->diplom->recenz}
                	{else}
                		{$preview->diplom->recenz_id}
                	{/if}
				{elseif $preview->diplom_id==0}
					{if CStaffManager::getDiplomByStudent($preview->student_id)->recenz_id==0}
						{CStaffManager::getDiplomByStudent($preview->student_id)->recenz}
					{else}
						{CStaffManager::getPersonById(CStaffManager::getDiplomByStudent($preview->student_id)->recenz_id)->fio}
					{/if}
                {/if}
			</td>
			<td>
                {$preview->date_preview|date_format:"d.m.Y"}
            </td>
			<td>
                {if (!is_null($preview->commission))}
                	{$preview->commission->name} 
                {/if}
               	{if $preview->commission->secretary_id != 0}
               		({CStaffManager::getPersonById({$preview->commission->secretary_id})->fio})
               	{/if}
            </td>
			<td>
                {$preview->comment}
            </td>
        </tr>
        {/foreach}
    </table>
{/if}
    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_diploms/diplom_preview/index.right.tpl"}
{/block}