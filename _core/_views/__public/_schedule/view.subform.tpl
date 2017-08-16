<td valign="top">
	{if (!$isPublic)}
		<a href="{$web_root}_modules/_schedule/index.php?action=add&nameId={$name->getId()}&redirect={CRequest::getString("action")}&nameInCell={$nameInCell}&
			year={$year->getId()}&day={$day}&number={$num}&yearPart={$yearPart->getId()}" title="Добавить" target="_blank">
            	<img src="{$web_root}images/new_elem.gif">
        </a>
        <br>
	{/if}
	{* дублирование отображения лабораторных работ в соседней ячейке *}
	{$number = $num-1}
	{counter start=0 print=false assign=countLabWork}
	{foreach CScheduleService::getScheduleByDayAndNumber($schedules, $day, $number)->getItems() as $schedule}
		{if ($schedule->kindWork->getAlias() == CScheduleKindWorkConstants::LAB_WORK)}
        	{counter}
        {/if}
	{/foreach}
	{counter start=0 print=false assign=countAllWork}
	{foreach CScheduleService::getScheduleByDayAndNumber($schedules, $day, $number)->getItems() as $schedule}
		{if ($schedule->kindWork->getAlias() == CScheduleKindWorkConstants::LAB_WORK)}
        	{CScheduleService::getCellForSchedule($schedule, $schedule->$nameInCell, true)}
        	{counter}
            {if ($countAllWork != $countLabWork)}
            	<hr>
            {/if}
        {/if}
	{/foreach}
	
	{* добавление <hr> перед выводом расписания, если есть лабораторные работы в ячейке *}
	{if ($countLabWork != 0 and CScheduleService::getScheduleByDayAndNumber($schedules, $day, $num)->getCount() != 0)}
		<hr>
	{/if}
	
	{* отображение самого расписания *}
	{$arrayLength = CScheduleService::getScheduleByDayAndNumber($schedules, $day, $num)->getCount()}
	{counter start=0 print=false assign=countSchedules}
	{foreach CScheduleService::getScheduleByDayAndNumber($schedules, $day, $num)->getItems() as $schedule}
		{if (!$isPublic)}
			<a href="{$web_root}_modules/_schedule/index.php?action=edit&id={$schedule->getId()}&nameId={$name->getId()}&redirect={CRequest::getString("action")}" title="Редактировать" target="_blank">
                <img src="{$web_root}images/toupdate.png">
            </a>
    	{/if}
		{CScheduleService::getCellForSchedule($schedule, $schedule->$nameInCell)}
		{if (!$isPublic)}
			<a href="#" class="icon-trash" title="Удалить" onclick="if (confirm('Действительно удалить запись {$schedule->getId()}?')) 
			{ location.href='index.php?action=delete&id={$schedule->getId()}&nameId={$name->getId()}&redirect={CRequest::getString("action")}'; }; return false;"></a>
        {/if}
        {counter}
        {if ($countSchedules != $arrayLength)}
        	<hr>
        {/if}
	{/foreach}
</td>