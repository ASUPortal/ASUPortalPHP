{extends file="_core.3col.tpl"}

{block name="localSearchContent"}
	<script>
	jQuery(document).ready(function(){
		jQuery("#isArchive").change(function(){
			window.location.href=web_root + "_modules/_corriculum/workplans.php?isArchive=" + (jQuery(this).is(":checked") ? "1":"0");
		});
    	jQuery("#selectAll").change(function(){
    		var items = jQuery("input[name='selectedDoc[]']")
            for (var i = 0; i < items.length; i++) {
                items[i].checked = this.checked;
            }
        });
	});
	</script>

	<div class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="person">В архиве</label>
			<div class="controls">
				{CHtml::checkBox("isArchive", "1", $isArchive, "isArchive")}
			</div>
		</div>
	</div>
{/block}

{block name="asu_center"}
	<h2>Рабочие программы</h2>
    {CHtml::helpForCurrentPage()}
    {include file="_core.searchLocal.tpl"}
    
    {if $plans->getCount() == 0}
		Нет планов для отображения
	{else}

		<form action="workplans.php" method="post" id="MainView">
	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th></th>
	            <th><input type="checkbox" id="selectAll"></th>
	            <th>№</th>
				<th></th>
	            <th>{CHtml::tableOrder("title_display", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("discipline_id", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("corriculum", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("year", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("profiles", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("authors", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("title", $plans->getFirstItem())}</th>
	        </tr>
	        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $plans->getItems() as $plan}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить рабочую программу дисциплины {$plan->discipline}')) { location.href='?action=delete&id={$plan->id}'; }; return false;"></a></td>
	            <td><input type="checkbox" value="{$plan->getId()}" name="selectedDoc[]"></td>
	            <td>{counter}</td>
	            <td><a href="?action=edit&id={$plan->getId()}" class="icon-pencil"></a></td>
	            <td>{$plan->title_display}</td>
	            <td>{$plan->discipline}</td>
	            <td><a href="{$web_root}_modules/_corriculum/?action=view&id={$plan->corriculumDiscipline->cycle->corriculum->getId()}">{$plan->corriculumDiscipline->cycle->corriculum->title}</a></td>
	            <td>{$plan->year}</td>
	            <td>{", "|join:$plan->profiles->getItems()}</td>
				<td>{", "|join:$plan->authors->getItems()}</td>
				<td>{$plan->title}</td>
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
