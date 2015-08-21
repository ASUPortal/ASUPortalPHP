{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Учебные материалы</h2>
	{if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}
	<script>
	   jQuery(document).ready(function(){
	   	var filters = new Object();
	   	{if !is_null($selectedUser)}
	   		filters.author = {$selectedUser};
	   	{/if}
	       function updateFilter() {
	       	var query = new Array();
	       	var filter = new Array();
	       	$.each(filters, function(key, value){
	       		if (value != 0) {
	           		filter[filter.length] = key + ":" + value;	
	       		}
	       	});
	       	query[query.length] = "action=view";
	       	query[query.length] = "filter=" + filter.join("_");
	       	window.location.href = "index.php?" + query.join("&");
	       }
	   	$("#user_id").change(function(){
	   		filters.author = $(this).val();
	   		updateFilter();
	   	});
	   });
	</script>
	<table border="0" width="100%" class="tableBlank">
		<tr>
			{if (CSession::getCurrentUser()->getLevelForCurrentTask() == 2 or CSession::getCurrentUser()->getLevelForCurrentTask() == 4)}
			<td valign="top">
				<div class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="person">Автор</label>
						<div class="controls">
							{CHtml::dropDownList("author", $users, $selectedUser, "user_id", "span12")} 
						</div>
					</div>
				</div>
			</td>
			{/if}
		</tr>
	</table>
    {if ($folders->getCount() == 0)}
        Нет учебных материалов.
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
			<tr>
	            <th></th>
	            <th></th>
	            <th>#</th>
	            <th>Предмет</th>
	        </tr>
		{counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
		{foreach $folders->getItems() as $folder}
		    <tr>
		    	<td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить предмет {$folder->getDiscipline()->getValue()}')) { location.href='?action=deleteDocument&id={CLibraryManager::getDocumentByFolderId($folder->getFolderIds()->getItem($author))->id}'; }; return false;"></a></td>
		        <td><a class="icon-edit" href="#" onclick="{ location.href='?action=editDocument&id={CLibraryManager::getDocumentByFolderId($folder->getFolderIds()->getItem($author))->id}&author:{$author}'; };" title="изменить"></a></td>
				<td>{counter}</td>
				<td>
					{if !is_null($folder->getDiscipline())}
	                	<a href="?action=viewFiles&id={$folder->getFolderIds()->getItem($author)}&filter=author:{$author}">{$folder->getDiscipline()->getValue()} ({$folder->getMaterialsCountBySubject($folder->getDiscipline()->id, $author)})</a>
	            	{/if}
	            </td>
			
			</tr>
		{/foreach}
		</table>
		{CHtml::paginator($paginator, "?action=view")}
    {/if}
{/block}

{block name="asu_right"}
	{include file="_library/index.right.tpl"}
{/block}