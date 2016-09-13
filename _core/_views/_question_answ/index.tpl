{extends file="_core.3col.tpl"} 
{block name="asu_center"}
<h2>Вопрос-ответ</h2>

{CHtml::helpForCurrentPage()}

	<script>
    /**
     * Очистка указанного фильтра
     * @param type
     */
    function removeFilter(type) {
        var filters = new Object();
        {if !is_null($selectedUser)}
            filters.user_id = {$selectedUser};
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
    	{if !is_null($selectedUser)}
    		filters.user = {$selectedUser};
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
        	window.location.href = "index.php?" + query.join("&");
        }
		jQuery("#showall").change(function(){
			{if $showAll}
				window.location.href = web_root + "_modules/_question_answ/index.php";
			{else}
				window.location.href = web_root + "_modules/_question_answ/index.php?action=index&showAll=1";
			{/if}
		});
    	$("#user_id").change(function(){
    		filters.user = $(this).val();
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
                    // выбран адресат
                    window.location.href = "?action=index&isArchive=1&filter=user:" + selected.object_id;
                } else if(selected.type == 2) {
                    // выбран вопрос
                    window.location.href = "?action=index&isArchive=1&filter=question:" + selected.object_id;
                }
                else if(selected.type == 3) {
                    // выбран ответ
                    window.location.href = "?action=index&isArchive=1&filter=answer:" + selected.object_id;
                }
                else if(selected.type == 4) {
                    // выбран ответ
                    window.location.href = "?action=index&isArchive=1&filter=contact:" + selected.object_id;
                }
            }
        });
    });
	</script>
	<table border="0" width="100%" class="tableBlank">
		<tr>
			{if (CSession::getCurrentUser()->getLevelForCurrentTask() == 2 or CSession::getCurrentUser()->getLevelForCurrentTask() == 4)}
			<td valign="top">
				<div class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="person">Пользователь</label>
						<div class="controls">
							{CHtml::dropDownList("user", $users, $selectedUser, "user_id", "span12")} 
							<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter(); return false; " title="очистить фильтры"/></span>
						</div>
					</div>
				</div>
			</td>
			<td valign="top">
				<p>
					<input type="text" id="search" style="width: 95%;" placeholder="Поиск">
				</p>
			</td>
			{else}
			<td valign="top" width="74%">
			</td>
			<td valign="top">
				<p>
					<input type="text" id="search" style="width: 95%;" placeholder="Поиск">
				</p>
			</td>
			{/if}
		</tr>
	</table>
	<form class="form-horizontal">
      <div class="control-group">
       	<label for="showall" class="control-label">Отражать удаленные записи</label>
      	<div class="controls">
			{CHtml::checkBox("showall", 1, $showAll)}
        </div>
      </div>
	</form>
	{if ($quests->getCount() == 0)} 
		Нет объектов для отображения 
	{else}
		<table
			class="table table-striped table-bordered table-hover table-condensed">
			<tr>
				<th></th>
				<th><input type="checkbox" id="selectAll"></th>
				<th>#</th>
				<th>{CHtml::tableOrder("datetime_quest", $quests->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("question_text", $quests->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("contact_info", $quests->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("datetime_answ", $quests->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("answer_text", $quests->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("st.name", $quests->getFirstItem(), true)}</th>
			{if (CSession::getCurrentUser()->getLevelForCurrentTask() == 2 or CSession::getCurrentUser()->getLevelForCurrentTask() == 4)}
				<th>{CHtml::tableOrder("quest.user_id", $quests->getFirstItem(), true)}</th>
			{/if}
			</tr>
			{counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
			{foreach $quests->getItems() as $quest}
			<tr>
				<td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить вопрос {$quest->question_text}')) { location.href='?action=delete&id={$quest->id}'; }; return false;"></a></td>
				<td><input type="checkbox" value="{$quest->getId()}" name="selectedDoc[]"></td>
				<td>{counter}</td>
				<td><a href="index.php?action=edit&id={$quest->getId()}">{$quest->datetime_quest}</a></td>
				<td>{$quest->question_text}</td>
				<td>{$quest->contact_info}</td>
				<td>{$quest->datetime_answ}</td>
				<td>{$quest->answer_text}</td>
				<td>
					{if $quest->status != 0}
						{$quest->stat->getValue()}
					{/if}
				</td>
			{if (CSession::getCurrentUser()->getLevelForCurrentTask() == 2 or CSession::getCurrentUser()->getLevelForCurrentTask() == 4)}
				<td>
					{if $quest->user_id != 0}
	                    <a href="{$web_root}_modules/_staff/?action=edit&id={$quest->user->getId()}">{$quest->user->getName()}</a>
	                {/if}
				</td>
			{/if}
			</tr>
			{/foreach}
		</table>
		{CHtml::paginator($paginator, "?action=index")} 
	{/if} 
{/block} 

{block name="asu_right"} 
	{include file="_question_answ/index.right.tpl"} 
{/block}
