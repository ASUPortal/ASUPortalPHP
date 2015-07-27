{extends file="_core.3col.tpl"}

{block name="asu_center"}
	{if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}
    <h2 style="text-align:center">{$group->name}</h2>
    <br>

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Студенты: ({$group->getStudents()->getCount()})</th>
            <th>Расписание</th>
            <th>Куратор</th>
            <th>Староста</th>
            <th>Описание</th>
        </tr>
            <tr>
				<td>
					{if $group->getStudents()->getCount() != 0}
					<ul>
						{foreach $group->getStudents()->getItems() as $student}
							<li>{$student->fio}</li>
						{/foreach}
					</ul>
					{else}
						записей нет
					{/if}
				</td>
				<td>
					{if {$group->getSchedule()->getCount()}!=0}
						<a href="{$web_root}p_time_table.php?onget=1&idlect={$group->getSchedule()->getFirstItem()->grup}&gr_mode=1">посмотреть</a>
		         	{else}
		         		расписания на портале нет
					{/if}
				</td>
				<td>		
					{if !is_null($group->curator)}
						{$group->curator->getName()}
					{/if}
				</td>
				<td>
					{if !is_null($group->monitor)}
						{$group->monitor->getName()}
					{/if}
				</td>
				<td>{$group->comment}</td>
            </tr>
    </table>

{/block}

{block name="asu_right"}
	{include file="__public/_student_groups/view.right.tpl"}
{/block}
