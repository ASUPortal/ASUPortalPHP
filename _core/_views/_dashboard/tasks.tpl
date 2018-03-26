{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Список Ваших задач портала</h2>
    {CHtml::helpForCurrentPage()}
    
    {if ($tasks->getCount() == 0)}
        Нет объектов для отображения
    {else}
	<table class="table table-striped table-bordered table-hover table-condensed">
	    <tr>
	        <th>#</th>
	        <th>Наименование</th>
	        <th>Группы</th>
	    </tr>
	    {foreach $tasks as $task}
			<tr>
				<td width="5%">{counter}</td>
				<td width="30%"><a href={$web_root}{$task->url}>{$task->name}</a></td>
				<td>
					<ul>
						{foreach CStaffManager::getRolesByTaskByUser(CStaffManager::getUserRole($task->id), CSession::getCurrentUser())->getItems() as $item}
							<li><font color="{$item->group->color_mark}">{$item->group->comment}</font></li>
						{/foreach}
					</ul>
				</td>
			</tr>
	    {/foreach}
	</table>
    {/if}
{/block}

{block name="asu_right"}
	{include file="_dashboard/common.right.tpl"}
{/block}