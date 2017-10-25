{extends file="_core.3col.tpl"} 
{block name="asu_center"}
<h2>Данные об обучающихся аспирантах</h2>

{CHtml::helpForCurrentPage()}

	<script>
    /**
     * Очистка указанного фильтра
     * @param type
     */
    function removeFilter(type) {
        var filters = new Object();
        {if !is_null($selectedPerson)}
            filters.kadri_id = {$selectedPerson};
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
    	{if !is_null($selectedPerson)}
    		filters.person = {$selectedPerson};
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
    	$("#kadri_id").change(function(){
    		filters.person = $(this).val();
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
                    // выбрано фио
                    window.location.href = "?action=index&isArchive=1&filter=fio:" + selected.object_id;
                } else if(selected.type == 2) {
                    // выбран комментарий
                    window.location.href = "?action=index&isArchive=1&filter=tema:" + selected.object_id;
                }
                else if(selected.type == 3) {
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
						<label class="control-label" for="person">Руководитель</label>
						<div class="controls">
							{CHtml::dropDownList("person", $managers, $selectedPerson, "kadri_id", "span12")} 
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
	{if ($dissers->getCount() == 0)} 
		Нет объектов для отображения 
	{else}

		<table
			class="table table-striped table-bordered table-hover table-condensed">
			<tr>
				<th><input type="checkbox" id="selectAll"></th>
				<th>#</th>
				<th>{CHtml::tableOrder("person.fio", $dissers->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("science_spec_id", $dissers->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("study_form_id", $dissers->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("scinceMan", $dissers->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("tema", $dissers->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("god_zach", $dissers->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("date_end", $dissers->getFirstItem(), true)}</th>
				<th>{CHtml::tableOrder("comment", $dissers->getFirstItem(), true)}</th>
				<th>Портфолио</th>
			</tr>
			{counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
			{foreach $dissers->getItems() as $disser}
			<tr>
				<td><input type="checkbox" value="{$disser->getId()}" name="selectedDoc[]"></td>
				<td>{counter}</td>
				<td>
					{if !is_null($disser->person)}
	                    <a href="{$web_root}_modules/_staff/index.php?action=edit&id={$disser->person->getId()}">{$disser->person->getName()}</a>
	                {/if}
				</td>
				<td>
					{if $disser->science_spec_id != 0}
						{$disser->scienceSpec->getValue()}
					{/if}
				</td>
				<td>
					{if $disser->study_form_id != 0}
						{$disser->educationForm->getValue()}
					{/if}
				</td>
				<td>
					{if $disser->scinceMan != 0}
						{$disser->scienceManager->fio}
					{/if}
				</td>
				<td>{$disser->tema}</td>
				<td>{$disser->god_zach}</td>
				<td>{$disser->date_end}</td>
				<td>{$disser->comment}</td>
				<td>
					<ul>
						{foreach CStaffManager::getPerson({$disser->person->getId()})->portfoliopapers->getItems() as $portfolio}
					        <li>
					            {$portfolio->tema}<br>{CHtml::activeAttachPreview("file_attach", $portfolio, true)}
					        </li>
						{/foreach}
					</ul>
			    </td>
			</tr>
			{/foreach}
		</table>

		{CHtml::paginator($paginator, "?action=index")} 
	{/if} 
{/block} 

{block name="asu_right"} 
	{include file="_aspirants_view/index.right.tpl"} 
{/block}
