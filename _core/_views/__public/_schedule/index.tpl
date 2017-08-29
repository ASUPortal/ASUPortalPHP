{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2 style="text-align:center">Расписание по группе</h2>
    <div class=text style="text-align:center">выберите первую букву названия</div>
    <br>
	<div class=text style="text-align:center">
		<div style="font-size:18pt;">
			{if ($resRusLetters)}
	        	{foreach $resRusLetters as $name=>$count}
	        		{if (array_key_exists($letterId, $firstLet))}
	        			{if ($firstLet[$letterId]=={$name})}
	        				<font size=+3>{$name}<font color=#e70013><sub>{$count}</sub></font></font>
	        			{else}
	        				<a href="?getsub={array_search({$name},$firstLet)}" title="в категории записей: {$count}">{$name}<font color=#e70013><sub>{$count}</sub></font></a>
	        			{/if}
	        		{else} 
	        			<a href="?getsub={array_search({$name},$firstLet)}" title="в категории записей: {$count}">{$name}<font color=#e70013><sub>{$count}</sub></font></a>
	        		{/if}
	        	{/foreach}
	        {else}
			<div class=text><b>записей не найдено</b></div>
	        {/if}
	        <br><br><a href="?getallsub=1">все</a><br><BR>
		</div>
	</div>

    {if ($groups->getCount() == 0)}
        Нет объектов для отображения
    {else}
	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th>#</th>
	            <th>{CHtml::tableOrder("name", $groups->getFirstItem())}</th>
	            <th>Расписание</th>
	        </tr>
	        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $groups->getItems() as $group}
	            <tr>
	                <td>{counter}</td>
	                <td><a href="{$web_root}_modules/_student_groups/public.php?action=view&id={$group->getId()}" title="О группе">{$group->name}</a></td>
	                <td>
						{if {$group->getSchedule()->getCount()}!=0}
			    			<a href="{$web_root}_modules/_schedule/public.php?action=viewGroups&id={$group->getSchedule()->getFirstItem()->grup}">посмотреть</a>
			         	{else}
			         		расписания на портале нет
			       		{/if}
	                </td>
	            </tr>
	        {/foreach}
	    </table>
    {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
	{include file="__public/_schedule/common.right.tpl"}
{/block}
