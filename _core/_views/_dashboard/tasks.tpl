{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Список Ваших задач портала</h2>
    {CHtml::helpForCurrentPage()}
    
    {if ($tasks->getCount() == 0)}
        Нет объектов для отображения
    {else}
		{foreach $tasks->getItems() as $task}
			<div>
				<ul><a href={$web_root}{$task->url}>{$task->name}</a></ul>
			</div>
		{/foreach}
    {/if}
{/block}

{block name="asu_right"}
	{include file="_dashboard/index.right.tpl"}
{/block}