{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Посещаемость</h2>

    {CHtml::helpForCurrentPage()}
    
    <form action="visit.php" method="post" class="form-horizontal">

    {CHtml::hiddenField("action", "saveEdit")}
    {CHtml::hiddenField("id", $protocol->getId())}
    
    {CHtml::errorSummary($protocol)}
    
		<table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th>{CHtml::tableOrder("kadri_id", $protocol->visits->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("visit_type", $protocol->visits->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("matter_text", $protocol->visits->getFirstItem())}</th>
	        </tr>
	        {foreach $protocol->visits->getItems() as $visit}
	            <tr>
	                <td width="50%">
	                    {CHtml::textField($visit->person->getName(), $visit->person->getName(), "", "", 'style="width: 100%;"')}
	                </td>
	                <td align="left" width="10%">
	                    {CHtml::checkBox($visit->getId(), "1", $visit->visit_type)}
	                </td>
	                <td width="40%">
	                	{if $visit->matter_text != ""}
	                		{CHtml::textField($visit->matter_text, $visit->matter_text, "", "", 'style="width: 100%;"')}
	                	{else}
	                		{CHtml::textField($visit->matter_text, "", "", "", 'style="width: 100%;"')}
	                	{/if}
	                </td>
	            </tr>
	        {/foreach}
		</table>
	
	    <div class="control-group">
	        <div class="controls">
	            {CHtml::submit("Сохранить", false)}
	        </div>
	    </div>
	</form>

{/block}

{block name="asu_right"}
	{include file="_course_projects/tasks/common.right.tpl"}
{/block}