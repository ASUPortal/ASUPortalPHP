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
			<th width="30%">Общее количество предзащит</th>
			<td colspan="2">{$count_previews}</td>
	    </tr>
	</table>

	<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
	        <th colspan="3">Количество предзащит за текущий год</th>
    	</tr>
		<tr>
			<td width="30%"></td> 
			<th>Зимой</th>
			<th>Летом</th>
	    </tr>
	    <tr>
			<th>Всего</th>
			<td>{$count_previews_winter}</td>
			<td>{$count_previews_summer}</td>
	    </tr>
	    <tr>
	        <th>Прошедшие предзащиту</th>
	        <td>{$count_previews_winter_complete}</td>
	        <td>{$count_previews_summer_complete}</td>
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
        {foreach $date_previews as $key => $value}
        <tr>
            <td>{counter}</td>
			<td>{$key}</td>
            <td>{$value}</td>
        </tr>
        {/foreach}
    </table>
    
{/block}

{block name="asu_right"}
	{include file="_diploms/diplom_preview/edit.right.tpl"}
{/block}