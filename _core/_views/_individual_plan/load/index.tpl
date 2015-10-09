{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Индивидуальные планы преподавателей</h2>

    {CHtml::helpForCurrentPage()}
    
    <script>
    /**
     * Очистка указанного фильтра
     * @param type
     */
    function removeFilter(type) {
        var filters = new Object();
        {if !is_null($selectedYear)}
            filters.id = {$selectedYear};
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
    	{if !is_null($selectedYear)}
    		filters.year = {$selectedYear};
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
        	window.location.href = "load.php?" + query.join("&");
        }
    	$("#id").change(function(){
    		filters.year = $(this).val();
    		updateFilter();
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
                    // выбрано фио
                    window.location.href = "?action=index&filter=fio:" + selected.object_id;
                }
            }
        });
    });
	</script>
	{if (CSession::getCurrentUser()->getLevelForCurrentTask() == 4)}
		<table border="0" width="100%" class="tableBlank">
			<tr>
				<td valign="top">
					<div class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="year">Учебный год</label>
							<div class="controls">
								{CHtml::dropDownList("year", $years, $selectedYear, "id", "span12")} 
								<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter(); return false; " title="очистить фильтры"/></span>
							</div>
						</div>
					</div>
				</td>
				<td valign="top">
					<p>
						<input type="text" id="search" style="width: 96%;"
							placeholder="Поиск">
					</p>
				</td>
			</tr>
		</table>
	{/if}
	

    {if $persons->getCount() == 0}
        <div class="alert">
            Нет документов для отображения
        </div>
    {else}

        <script>
            jQuery(document).ready(function(){
                jQuery("#selectAll").change(function(){
                    var items = jQuery("input[name='selectedDoc[]']")
                    for (var i = 0; i < items.length; i++) {
                        items[i].checked = this.checked;
                    }
                });
            });
        </script>

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th><input type="checkbox" id="selectAll"></th>
                <th>{CHtml::tableOrder("fio", $persons->getFirstItem())}</th>
                <th>Год</th>
            </tr>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $persons->getItems() as $person}
            <tr>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">{counter}</td>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">
                    <input type="checkbox" value="{$person->getId()}" name="selectedDoc[]">
                </td>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">
                    <a href="load.php?action=view&id={$person->getId()}">
                        {$person->fio}
                    </a>
                </td>
                {if $person->getIndPlansByYears()->getCount() == 0}
                    <td>Нет информации</td>
                {/if}
            </tr>
                {foreach $person->getIndPlansByYears()->getItems() as $year=>$load}
                    <tr>
                        <td>
                            {if !is_null(CTaxonomyManager::getYear($year))}
                                <a href="load.php?action=view&id={$person->getId()}&year={$year}">
                                    {CTaxonomyManager::getYear($year)->getValue()}
                                </a>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/common.right.tpl"}
{/block}