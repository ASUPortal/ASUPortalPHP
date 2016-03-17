{extends file="_core.3col.tpl"}

{block name="asu_center"}
	
	<h2>Список публикаций</h2>
	{CHtml::helpForCurrentPage()}
    
    <script>
	    /**
	     * Очистка указанного фильтра
	     * @param type
	     */
	    function removeFilter(type) {
	        var filters = new Object();
	        var action = "?action=index";
	        window.location.href = action;
	    }
	    jQuery(document).ready(function(){
	    	var filters = new Object();
	    	{if !is_null($currentPerson)}
    			filters["kadri_id"] = {$currentPerson};
    		{/if}
	    	{if !is_null($currentPerson)}
	    		filters["p.kadri_id"] = {$currentPerson};
	    	{/if}
	    	{if !is_null($currentType)}
	    		filters["type.id"] = {$currentType};
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
	    		query[query.length] = "filter=" + filter.join("&filter=");
	    		query[query.length] = "action=index";
	    		window.location.href = "publications.php?" + query.join("&");
	    	}
	    	$("#show_types").click(function(){
	    		filters["type.id"] = $("#multiple").val();
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
	                    // выбрано название
	                    window.location.href = "?action=index&filter=title:" + selected.object_id;
	                }
	            }
	        });
	    });
        jQuery(document).ready(function(){
            jQuery("#person_selector").change(function(){
                window.location.href=web_root + "_modules/_staff/publications.php?kadri_id=" + jQuery(this).val();
            });
        });
    </script>
    <form action="publications.php" method="post" enctype="multipart/form-data" class="form-horizontal">
	<table border="0" width="100%" class="tableBlank">
		<tr>
			<td valign="top">
				<div class="form-horizontal">
			        <div class="control-group">
			            <label class="control-label" for="p.kadri_id">Сотрудник</label>
			            <div class="controls">
			                {CHtml::dropDownList("p.kadri_id", $personList, $currentPerson, "person_selector", "span12")}
			                {if !is_null($currentPerson)}
			                	<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter(); return false; " title="очистить фильтры""/></span>
			                {/if}
			            </div>
			        </div>
			    </div>
			</td>
		</tr>
	</table>
	<table border="0" width="100%" class="tableBlank">
		<tr>
			<td width="50%">			
				<div class="form-horizontal">
        			<div class="control-group">
            			<label class="control-label" for="type.id">Вид публикации</label>
            			<div class="controls">
            				<p>
            					<select id="multiple" multiple style="width: 90%; ">
            					{foreach $izdanTypes as $key=>$value}
									<option value={$key}>{$value}</option>
								{/foreach}
								</select>
							</p>
            			</div>
        			</div>
    			</div>
    		</td>
    		<td valign="top" width="10%">
    			<div id="show_types"><input name="" type="button" class="btn" value="Показать"></div>
    		</td>
    		<td valign="top" width="40%">
				<p>
					<input type="text" id="search" style="width: 96%;" placeholder="Поиск">
				</p>
			</td>
		</tr>
    </table>
    </form>

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::activeViewGroupSelect("id", $objects->getFirstItem(), true)}</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("name", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("page_range", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("bibliografya", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("authors_all", $objects->getFirstItem())}</th>
                    <th>В том числе с кафедры</th>
                    <th>{CHtml::tableOrder("grif", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("publisher", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("year", $objects->getFirstItem(), true)}</th>
                    <th>{CHtml::tableOrder("approve_date", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("type_book", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("copy", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить публикация')) { location.href='publications.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{CHtml::activeViewGroupSelect("id", $object, false, true)}</td>
                    <td>{counter}</td>
                    <td><a href="publications.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->name}</td>
                    <td>{$object->page_range}</td>
                    <td>{$object->bibliografya}</td>
                    <td>
                        {$object->authors_all}
                        <ol>
                        {foreach $object->authors->getItems() as $author}
                            <li>{$author->getName()}</li>
                        {/foreach}
                        </ol>
                    </td>
                    <td>
                        {if $object->authors->getCount() > 0}
                            {$object->authors->getCount()}
                        {/if}
                    </td>
                    <td>{$object->grif}</td>
                    <td>{$object->publisher}</td>
                    <td>{$object->year}</td>
                    <td>{$object->approve_date}</td>
                    <td>
                        {if !is_null($object->type)}
                            {$object->type->getValue()}
                        {/if}
                    </td>
                    <td>{CHtml::activeAttachPreview("copy", $object, true)}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "publications.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_staff/publications/common.right.tpl"}
{/block}
