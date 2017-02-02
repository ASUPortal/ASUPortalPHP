{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Курсовое проектирование</h2>
    {CHtml::helpForCurrentPage()}
    
	{if $courseProjects->getCount() == 0}
		Нет объектов для отображения
	{else}
		<form action="index.php" method="post" id="MainView">
	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th></th>
	            <th>#</th>
	            <th>{CHtml::activeViewGroupSelect("id", $courseProjects->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("group_id", $courseProjects->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("discipline_id", $courseProjects->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("lecturer_id", $courseProjects->getFirstItem(), true)}</th>
	        </tr>
	        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $courseProjects->getItems() as $courseProject}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить запись {$courseProject->group->getName()}')) { location.href='?action=delete&id={$courseProject->id}'; }; return false;"></a></td>
	            <td>{counter}</td>
	            <td>{CHtml::activeViewGroupSelect("id", $courseProject, false, true)}</td>
	            <td><a href="?action=edit&id={$courseProject->getId()}">{$courseProject->group->getName()}</a></td>                       
	            <td>{$courseProject->discipline->getValue()}</td>
	            <td>{$courseProject->lecturer->getName()}</td>
	        </tr>
	        {/foreach}
	    </table>
	    </form>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
	{include file="_course_projects/index.right.tpl"}
{/block}
