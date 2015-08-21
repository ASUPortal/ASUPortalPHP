{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Рабочие программы</h2>
    {CHtml::helpForCurrentPage()}
    
    {if $plans->getCount() == 0}
		Нет планов для отображения
	{else}
		<form action="workplans.php" method="post" id="MainView">
	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th></th>
	            <th><input type="checkbox" id="selectAll"></th>
	            <th>№</th>
	            <th>{CHtml::tableOrder("title", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("department_id", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("approver_post", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("approver_name", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("direction_id", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("profiles", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("qualification_id", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("edufaction_form_id", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("year", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("intended_for", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("author_id", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("position", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("disciplinesBefore", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("disciplinesAfter", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("project_description", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("education_technologies", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("hardware", $plans->getFirstItem())}</th>
	        </tr>
	        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $plans->getItems() as $plan}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить план {$plan->title}')) { location.href='?action=delete&id={$plan->id}'; }; return false;"></a></td>
	            <td>
                    <input type="checkbox" value="{$plan->getId()}" name="selectedDoc[]">
                </td>
	            <td>{counter}</td>
	            <td><a href="?action=edit&id={$plan->getId()}">{$plan->title}</a></td>   
	            <td>{$plan->department_id}</td>
	            <td>{$plan->approver_post}</td>                    
	            <td>{$plan->approver_name}</td>
	            <td>{$plan->direction_id}</td>
	            <td>
	            	{foreach $plan->profiles->getItems() as $profil}
	            		{$profil}
	            	{/foreach}
	            </td>              
	            <td>{$plan->qualification_id}</td>
	            <td>{$plan->edufaction_form_id}</td>
	            <td>{$plan->year}</td>                    
	            <td>{$plan->intended_for}</td>
	            <td>{$plan->author_id}</td>
	            <td>{$plan->position}</td>                    
	            <td>
	            	{foreach $plan->disciplinesBefore->getItems() as $discipline}
	            		{$discipline}
	            	{/foreach}
	            </td>
	            <td>{foreach $plan->disciplinesAfter->getItems() as $discipline}
	            		{$discipline}
	            	{/foreach}
	            </td>
	            <td>{$plan->project_description}</td>                    
	            <td>{$plan->education_technologies}</td>
	            <td>{$plan->hardware}</td>
	        </tr>
	        {/foreach}
	    </table>
	    </form>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
{include file="_corriculum/_workplan/workplan/common.right.tpl"}
{/block}
