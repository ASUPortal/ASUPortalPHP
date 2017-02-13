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
	            <th>Учебный план</th>
	            <th>Дисциплина в учебном плане</th>
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
	            <td>
	            	{if !is_null($courseProject->group)}
			    		{if !is_null($courseProject->group->corriculum)}
			    			<a href="{$web_root}_modules/_corriculum/index.php?action=edit&id={$courseProject->group->corriculum->getId()}" target="_blank">{$courseProject->group->corriculum->title}</a>
			    		{else}
			    			В <a href="{$web_root}_modules/_student_groups/index.php?action=edit&id={$courseProject->group->getId()}" target="_blank">группе</a> не указан учебный план!
			    		{/if}
		    		{/if}
		    	</td>
		    	<td>
	            	{if !is_null($courseProject->group)}
			    		{if !is_null($courseProject->group->corriculum)}
				    		{foreach $courseProject->group->corriculum->cycles as $cycle}
			    				{foreach $cycle->allDisciplines as $discipline}
			    					{if $courseProject->discipline->getId() == $discipline->discipline->getId()}
			    						<a href="{$web_root}_modules/_corriculum/disciplines.php?action=edit&id={$discipline->getId()}" target="_blank">{$discipline->discipline->getValue()}</a>
			    					{/if}
			    				{/foreach}
			    			{/foreach}
			    		{/if}
		    		{/if}
		    	</td>
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
