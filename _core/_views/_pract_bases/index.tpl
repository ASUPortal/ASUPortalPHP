{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Базы практики студентов</h2>

    {CHtml::helpForCurrentPage()}

    <script>
    /**
     * Очистка указанного фильтра
     * @param type
     */
    function removeFilter(type) {
        var filters = new Object();
        {if !is_null($selectedTown)}
            filters.town_id = {$selectedTown};
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
    	{if !is_null($selectedTown)}
    		filters.town = {$selectedTown};
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
    		query[query.length] = "filter=" + filter.join("_");
    		query[query.length] = "action=index";
    		window.location.href = "index.php?" + query.join("&");
    	}
    	$("#town_id").change(function(){
    		filters.town = $(this).val();
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
                    // выбрано название
                    window.location.href = "?action=index&filter=name:" + selected.object_id;
                } else if(selected.type == 2) {
                    // выбран комментарий
                    window.location.href = "?action=index&filter=comment:" + selected.object_id;
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
            			<label class="control-label" for="town">Город</label>
            			<div class="controls">
                			{CHtml::dropDownList("town", $towns, $selectedTown, "town_id", "span12")}
                			{if !is_null($selectedTown)}
                    			<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('town'); return false; "/></span>
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
    {if ($practics->getCount() == 0)}
        Нет объектов для отображения
    {else}

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th></th>
				<th><input type="checkbox" id="selectAll"></th>
                <th>#</th>
                <th>{CHtml::tableOrder("name", $practics->getFirstItem(), true)}</th>
                <th>{CHtml::tableOrder("towns.name", $practics->getFirstItem(), true)}</th>
                <th>Дипломов</th>
                <th>{CHtml::tableOrder("comment", $practics->getFirstItem(), true)}</th>
            </tr>
            {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $practics->getItems() as $practic}
                <tr>
                    <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить базу практики {$practic->name}')) { location.href='?action=delete&id={$practic->id}'; }; return false;"></a></td>
                    <td>
						<input type="checkbox" value="{$practic->getId()}" name="selectedDoc[]">
					</td>
                    <td>{counter}</td>
                    <td width=30%><a href="index.php?action=edit&id={$practic->getId()}">{$practic->name}</a></td>
                    <td>
                    	{if $practic->town_id != 0}
	                		{$practic->town->getValue()}
	                	{/if}
	                </td>
                    <td>{$practic->getDiplomsCount()}</td>
                    <td>{$practic->comment}</td>
                </tr>
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_pract_bases/index.right.tpl"}
{/block}