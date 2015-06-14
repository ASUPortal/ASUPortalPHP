{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Статистика по предзащитам</h2>

    {CHtml::helpForCurrentPage()}
    
<script>
    jQuery(document).ready(function(){
    	var isArchive = false;
    	{if $isArchive}
    		isArchive = true;
    	{/if}
    });
</script>
	<table class="table table-bordered">
		<tr>
			<th width="30%">Общее количество защит</th>
			<td colspan="2">{$count_all}</td>
	    </tr>
		<tr>
			<th width="30%">Общее количество предзащит</th>
			<td colspan="2">{$count_previews}</td>
	    </tr>
	</table>

	<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
	        <th colspan="3">За текущий год</th>
    	</tr>
		<tr>
			<td width="30%"></td> 
			<th>Зимой</th>
			<th>Летом</th>
	    </tr>
	    <tr>
			<th>Всего защит</th>
			<td>{$count_winter_all}</td>
			<td>{$count_summer_all}</td>
	    </tr>
		<tr>
			<th>Из них не имеющие предзащиты</th>
			<td><a href="{$web_root}_modules/_diploms/index.php?action=index&winterNotPreviews=1">{$count_not_previews_winter}</a></td>
			<td><a href="{$web_root}_modules/_diploms/index.php?action=index&summerNotPreviews=1">{$count_not_previews_summer}</a></td>
	    </tr>
		<tr>
			<th>Количество предзащит</th>
			<td><a href="{$web_root}_modules/_diploms/preview.php?action=index&winterPreviews=1">{$count_previews_winter}</a></td>
			<td><a href="{$web_root}_modules/_diploms/preview.php?action=index&summerPreviews=1">{$count_previews_summer}</a></td>
	    </tr>
	    <tr>
	        <th>Прошедшие предзащиту</th>
	        <td><a href="{$web_root}_modules/_diploms/preview.php?action=index&winterCompletePreviews=1">{$count_previews_winter_complete}</a></td>
	        <td><a href="{$web_root}_modules/_diploms/preview.php?action=index&summerCompletePreviews=1">{$count_previews_summer_complete}</a></td>
	    </tr>
	    <tr>
	        <th>Не прошедшие предзащиту</th>
	        <td><a href="{$web_root}_modules/_diploms/preview.php?action=index&winterNotComplete=1">{$count_previews_winter_not_complete}</a></td>
	        <td><a href="{$web_root}_modules/_diploms/preview.php?action=index&summerNotComplete=1">{$count_previews_summer_not_complete}</a></td>
	    </tr>
	</table>
    
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>#</th>
            <th>Дата предзащиты</th>
            <th>Количество предзащит</th>
        </tr>
        {foreach $date_previews as $date_preview => $count}
        <tr>
            <td>{counter}</td>
			<td>{$date_preview}</td>
			<td>{$count}</td>
        </tr>
        {/foreach}
    </table>
    
{/block}

{block name="asu_right"}
	{include file="_diploms/diplom_preview/edit.right.tpl"}
{/block}