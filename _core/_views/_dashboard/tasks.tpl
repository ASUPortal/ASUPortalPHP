{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Список Ваших задач портала</h2>
    {CHtml::helpForCurrentPage()}
    
    {if (count($tasks) == 0)}
        Нет объектов для отображения
    {else}
	<table class="table table-striped table-bordered table-hover table-condensed">
	    <tr>
	        <th>#</th>
	        <th>Наименование</th>
	    </tr>
	    {foreach $tasks as $url=>$name}
			<tr>
				<td>{counter}</td>
				<td><a href={$web_root}{$url}>{$name}</a></td>
			</tr>
	    {/foreach}
	</table>
    {/if}
{/block}

{block name="asu_right"}
	{include file="_dashboard/index.right.tpl"}
{/block}