{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Темы ВКР</h2>
    {CHtml::helpForCurrentPage()}
    
    <script>
    /**
     * Очистка указанного фильтра
     * @param type
     */
    function removeFilter(type) {
        var filters = new Object();
        {if !is_null($currentPerson)}
            filters.kadri_id = {$currentPerson};
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
    	{if !is_null($currentPerson)}
    		filters.person = {$currentPerson};
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
    		query[query.length] = "filter=" + filter.join("_");
    		query[query.length] = "action=index";
    		window.location.href = "index.php?" + query.join("&");
    	}
    	$("#kadri_id").change(function(){
    		filters.person = $(this).val();
    		updateFilter();
    	});
    	$("#group_id").change(function(){
    		filters.group = $(this).val();
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
                    // выбрана тема
                    window.location.href = "?action=index&isArchive=1&filter=theme:" + selected.object_id;
                } else if(selected.type == 2) {
                    // выбран студент
                    window.location.href = "?action=index&isArchive=1&filter=student:" + selected.object_id;
                } else if(selected.type == 3) {
                    // выбрана степень утверждения диплома
                	window.location.href = "?action=index&isArchive=1&filter=confirm:" + selected.object_id;
                } else if(selected.type == 4) {
                    // выбрано место практики 
                	window.location.href = "?action=index&isArchive=1&filter=pract:" + selected.object_id;
                } else if(selected.type == 5) {
                    // выбрано место практики по id
                	window.location.href = "?action=index&isArchive=1&filter=practId:" + selected.object_id;
                } else if(selected.type == 6) {
                    // выбран руководитель
                	window.location.href = "?action=index&isArchive=1&filter=person:" + selected.object_id;
                } else if(selected.type == 7) {
                    // выбрана группа
                	window.location.href = "?action=index&isArchive=1&filter=group:" + selected.object_id;
                } else if(selected.type == 8) {
                    // выбран ин.яз.
                	window.location.href = "?action=index&isArchive=1&filter=foreign:" + selected.object_id;
                } else if(selected.type == 9) {
                    // выбран рецензент
                	window.location.href = "?action=index&isArchive=1&filter=recenz:" + selected.object_id;
                } else if(selected.type == 10) {
                    // выбрана оценка
                	window.location.href = "?action=index&isArchive=1&filter=mark:" + selected.object_id;
                } else if(selected.type == 11) {
                    // выбран комментарий
                	window.location.href = "?action=index&isArchive=1&filter=comment:" + selected.object_id;
                }
            }
        });
    });
	</script>
	<table border="0" width="100%" class="tableBlank">
		<tr>
			<td>
				<div class="form-horizontal">
        			<div class="control-group">
            			<label class="control-label" for="person">Руководитель</label>
            			<div class="controls">
                			{CHtml::dropDownList("person", $diplomManagers, $currentPerson, "kadri_id", "span12")}
                			{if !is_null($currentPerson)}
                    			<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('person'); return false; "/></span>
                			{/if}  
            			</div>
        			</div>
    			</div>
    			<div class="form-horizontal">
        			<div class="control-group">
            			<label class="control-label" for="group">Группа</label>
            			<div class="controls">
                			{CHtml::dropDownList("group", $studentGroups, $currentGroup, "group_id", "span12")}
                			{if !is_null($currentGroup)}
                    			<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('group'); return false; "/></span>
                			{/if}  
            			</div>
        			</div>
    			</div>
			</td>
      		<td valign="top">
				<p>
					<input type="text" id="search" style="width: 96%; " placeholder="Поиск">
				</p>
				<p align="center">
					<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('group'); return false; " title="очистить фильтры"/></span>
				</p>
			</td>
		</tr>
	</table>
    
	{if $diploms->getCount() == 0}
		Нет ВКР для отображения
	{else}
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
                jQuery.each(jQuery(".approveTheme"), function(key, value){
                    // раскрашиваем ячейку
                    colorizeCell(value);
                });
                /**
                 * Обрабатываем смену статуса утвержденности
                 */
                jQuery(".approveTheme").on("click", function(item){
                    var container = item.target || item.srcElement;
                    var id = jQuery(container).attr("asu-id");
                    jQuery.ajax({
                        url: web_root + "_modules/_diploms/",
                        beforeSend: function(){
                            jQuery(container).html('<i class="icon-signal"></i>');
                        },
                        cache: false,
                        context: item,
                        data: {
                            action: "updateThemeApprove",
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
            .approveTheme {
                cursor: pointer;
            }
            .approveTheme:hover {
                text-decoration: underline;
            }
        </style>
		<form action="index.php" method="post" id="MainView">
	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th></th>
	            <th><input type="checkbox" id="selectAll"></th>
	            <th>№</th>
	            <th>{CHtml::tableOrder("diplom_confirm", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("dipl_name", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("pract_place_id", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("prepod.fio", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("student.fio", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("st_group.name", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("dipl_prew.date_preview", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("date_act", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("foreign_lang", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("protocol_2aspir_id", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("recenz_id", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("study_mark", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("gak_num", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("comment", $diploms->getFirstItem(), true)}</th>
	        </tr>
	        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $diploms->getItems() as $diplom}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить тему ВКР {$diplom->dipl_name}')) { location.href='?action=delete&id={$diplom->id}'; }; return false;"></a></td>
	            <td>
                    <input type="checkbox" value="{$diplom->getId()}" name="selectedDoc[]">
                </td>
	            <td>{counter}</td>
				<td>
                    <span>
                        <span class="approveTheme" asu-id="{$diplom->getId()}" asu-color="{if is_null($diplom->confirmation)}white{else}{$diplom->confirmation->color_mark}{/if}">
                            {if is_null($diplom->confirmation)}
                                Не рассматривали
                            {else}
                                {$diplom->confirmation->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	            <td><a href="?action=edit&id={$diplom->getId()}">{$diplom->dipl_name}</a></td>                       
	            <td>
	                {if is_null($diplom->practPlace)}
	                    {$diplom->pract_place}
	                {else}
	                    {$diplom->practPlace->getValue()}
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->person)}
	                    <a href="{$web_root}_modules/_staff/?action=edit&id={$diplom->person->getId()}" title="о преподавателе">{$diplom->person->getName()}</a>
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->student)}
	                    <a href="{$web_root}_modules/_students/?action=edit&id={$diplom->student->getId()}" title="о студенте">{$diplom->student->getName()}</a>
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->student)}
	                    {if !is_null($diplom->student->getGroup())}
	                        {$diplom->student->getGroup()->getName()}
	                    {/if}
	                {/if}
	            </td>
	            <td>
	                {if $diplom->getLastPreviewDate() != "0"}
	                    {$diplom->getLastPreviewDate()|date_format:"d.m.Y"}
	                {/if}
	            </td>
	            <td>
	                {$diplom->date_act|date_format:"d.m.Y"}
	            </td>
	            <td>
	                {if !is_null($diplom->language)}
	                    {$diplom->language->getValue()}
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->recomendationProtocol)}
	                    {$diplom->recomendationProtocol->getNumber()} от {$diplom->recomendationProtocol->getDate()}
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->reviewer)}
	                    {$diplom->reviewer->getName()}
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->mark)}
	                    {$diplom->mark->getValue()}
	                {/if}
	            </td>
	            <td>
	                <a href="{$web_root}_modules/_state_attestation/?action=edit&id={$diplom->gak_num}">{$diplom->gak_num}</a>
	            </td>
	            <td>
	                {$diplom->comment}
	            </td>
	        </tr>
	        {/foreach}
	    </table>
	    </form>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
{include file="_diploms/common.right.tpl"}
{/block}
