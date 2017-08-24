Всего преподавателей с расписанием: {$countLecturers}<p>

{$cols_i = floor($countLecturers/6)}

{if $countLecturers == 0}
	Нет объектов для отображения
{else}
	<form action="public.php" method="post" id="mainView">
		<table class="table table-bordered table-hover table-condensed" border=1 cellspacing=0>
	    	<tr>
		        <td style="background-color: #EFEFFF;" width=100>&nbsp;</td>
		        {foreach CScheduleService::getLecturersWithSchedulesByYearAndPart($year, $yearPart)->getItems() as $lecturer}
		        	<td style="background-color: #EFEFFF; text-align:center;" width=150>
			        	<b>
			        		{if !$print}
				        		<a href="{$web_root}_modules/_schedule/{$link}?action=viewLecturers&id={$lecturer->getId()}" 
				        			style="text-decoration: none; color:#000000;" title="Посмотреть расписание" target="_blank">{$lecturer->getName()}
				        		</a>
			        		{else}
			        			{$lecturer->getName()}
			        		{/if}
			        	</b>
		        	</td>
		        {/foreach}
	        </tr>
	    	{for $day=1 to 6}
	    		<tr>
	    			{for $i=1 to $cols_i}
	    				<td style="background-color: #EFEFFF;" colspan=6><b>{$existDays[$day]}</b></td>
	    			{/for}
	    			<td style="background-color: #EFEFFF;" colspan={$countLecturers+1-$cols_i*6}><b>{$existDays[$day]}</b></td>
			        {for $num=1 to count($time)}
				        <tr>
					        <td style="background-color: #EFEFFF; vertical-align:middle;" width=100>&nbsp;<b>{$num}</b>&nbsp;{$time[$num]}</td>
					        {foreach CScheduleService::getLecturersWithSchedulesByYearAndPart($year, $yearPart)->getItems() as $lecturer}
				        		<td valign="top">
					        		{if (!$isPublic)}
					        			<a href="{$web_root}_modules/_schedule/index.php?action=add&nameId={$lecturer->getId()}&redirect={CRequest::getString("action")}&nameInCell={$nameInCell}&
										year={$year->getId()}&day={$day}&number={$num}&yearPart={$yearPart->getId()}" title="Добавить" target="_blank">
					        				<img src="{$web_root}images/new_elem.gif">
					        			</a>
					        			<br>
					        		{/if}
					        		{* дублирование отображения лабораторных работ в соседней ячейке *}
					        		{$number = $num-1}
					        		{counter start=0 print=false assign=countLabWork}
					        		{foreach CScheduleService::getScheduleByLecturerDayAndNumber($year, $yearPart, $lecturer, $day, $number)->getItems() as $schedule}
					        			{if ($schedule->kindWork->getAlias() == CScheduleKindWorkConstants::LAB_WORK)}
							            	{counter}
							            {/if}
					        		{/foreach}
					        		{counter start=0 print=false assign=countAllWork}
					        		{foreach CScheduleService::getScheduleByLecturerDayAndNumber($year, $yearPart, $lecturer, $day, $number)->getItems() as $schedule}
					        			{if ($schedule->kind == 1)}
							            	{CPublicScheduleController::getCellForAllSchedule($schedule)}
							            	{counter}
								            {if ($countAllWork != $countLabWork)}
								            	<hr>
								            {/if}
							            {/if}
					        		{/foreach}
					        		
					        		{* добавление <hr> перед выводом расписания, если есть лабораторные работы в ячейке *}
					        		{if ($countLabWork != 0 and CScheduleService::getScheduleByLecturerDayAndNumber($year, $yearPart, $lecturer, $day, $num)->getCount() != 0)}
					        			<hr>
					        		{/if}
					        		
					        		{* отображение самого расписания *}
					        		{$arrayLength = CScheduleService::getScheduleByLecturerDayAndNumber($year, $yearPart, $lecturer, $day, $num)->getCount()}
					        		{counter start=0 print=false assign=countSchedules}
					        		{foreach CScheduleService::getScheduleByLecturerDayAndNumber($year, $yearPart, $lecturer, $day, $num)->getItems() as $schedule}
					        			{if (!$isPublic)}
											<a href="{$web_root}_modules/_schedule/index.php?action=edit&id={$schedule->getId()}&nameId={$lecturer->getId()}&redirect={CRequest::getString("action")}" 
												title="Редактировать" target="_blank">
												<img src="{$web_root}images/toupdate.png">
											</a>
								    	{/if}
					        			{CPublicScheduleController::getCellForAllSchedule($schedule)}
					        			{if (!$isPublic)}
					        				<a href="#" class="icon-trash" title="Удалить" onclick="if (confirm('Действительно удалить запись {$schedule->getId()}?')) 
					        					{ location.href='index.php?action=delete&id={$schedule->getId()}&nameId={$lecturer->getId()}&redirect={CRequest::getString("action")}'; }; return false;"></a>
					        			{/if}
							            {counter}
							            {if ($countSchedules != $arrayLength)}
							            	<hr>
							            {/if}
					        		{/foreach}
				        		</td>
				        	{/foreach}
						</tr>
					{/for}
				</tr>
			{/for}
	    </table>
    </form>
{/if}

<div>Расписание звонков по времени</div>
<table border=1 style="text-align:center" cellspacing=0>
  	<tr><td style="font-weight:bold;text-align:center">№</td><td style="font-weight:bold;text-align:center" width=200>время</td></tr>
	<tr><td style="text-align:center">1</td><td>8.00-9.35</td></tr>
	<tr><td style="text-align:center">2</td><td>9.45-11.20</td></tr>
	<tr><td style="font-weight:bold;text-align:center" colspan=2><b>перерыв 40 мин.</b></td></tr>
	<tr><td style="text-align:center">3</td> <td>12.10-13.45</td></tr>
	<tr><td style="text-align:center">4</td> <td>13.55-15.30</td></tr>
	<tr><td style="font-weight:bold;text-align:center" colspan=2><b>перерыв 40 мин.</b></td></tr>
	<tr><td style="text-align:center">5</td> <td>16.10-17.45</td></tr>
	<tr><td style="text-align:center">6</td> <td>17.55-19.30</td></tr>
	<tr><td style="font-weight:bold;text-align:center" colspan=2>в середине занятия перерыв 5 мин.</td></tr>
</table>