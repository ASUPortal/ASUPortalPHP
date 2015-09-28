{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2 style="text-align:center">Преподаватели</h2>
    <div class=text style="text-align:center">выберите первую букву фамилии преподавателя</div>
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

    {if ($lects->getCount() == 0)}
        Нет объектов для отображения
    {else}
	    {include file="_core.searchLocal.tpl"}
	    	
        <table class="table table-striped table-bordered table-hover table-condensed">
			<tr>
	            <th>#</th>
	            <th>
	            	{if (CSettingsManager::getSettingValue("web_root") == "http://asu.ugatu.ac.ru/")}
	            		{CHtml::tableOrder("FIO", $lects->getFirstItem())}
	            	{else}	
	            		{CHtml::tableOrder("fio", $lects->getFirstItem())}
	            	{/if}
	            </th>
	            <th>Расписание</th>
	        </tr>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $lects->getItems() as $lect}
		    <tr>
		    	<td>{counter}</td>
		        <td><a href="index.php?action=view&id={$lect->getUser()->id}">
			        {if (CSettingsManager::getSettingValue("web_root") == "http://asu.ugatu.ac.ru/")}
			        	{$lect->FIO}</a>
			        {else}
			        	{$lect->fio}</a>
			        {/if}
		        </td>
		        <td>
					{if {$lect->getSchedule()->getCount()}!=0}
						{foreach $lect->getSchedule()->getItems() as $rasp}
		    				<a href="{$web_root}p_time_table.php?onget=1&idlect={$rasp->id}">посмотреть</a>
						{/foreach}
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
    {include file="__public/_lecturers/index.right.tpl"}
{/block}