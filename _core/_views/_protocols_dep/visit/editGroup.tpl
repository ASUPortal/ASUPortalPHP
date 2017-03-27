{extends file="_core.component.tpl"}

{block name="asu_center"}
    {CHtml::helpForCurrentPage()}
    
    <form action="visit.php" method="post" class="form-horizontal">

    {CHtml::hiddenField("action", "saveEdit")}
    {CHtml::hiddenField("id", $protocol->getId())}
    
    {CHtml::errorSummary($protocol)}
    
		<table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th>#</th>
	            <th>{CHtml::tableOrder("kadri_id", $protocol->visits->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("visit_type", $protocol->visits->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("matter_text", $protocol->visits->getFirstItem())}</th>
	            <th align="center">Пропустить</th>
	        </tr>
	        {counter start=0 print=false}
	        {foreach $protocol->visits->getItems() as $visit}
	            {CHtml::hiddenField(CProtocolManager::getFieldName($visit->getId(), "person"), $visit->person->getId())}
	            <tr>
	                <td>{counter}</td>
	                <td width="50%">
	                	{if $visit->visit_type != 1}
	                		{CHtml::label($visit->person->getName(), "", 'style="color:red;"', true)}
	                	{else}
	                		{CHtml::label($visit->person->getName(), "", 'style="color:green;"', true)}
	                	{/if}
	                </td>
	                <td width="10%" style="text-align:center;">
	                    {CHtml::checkBox(CProtocolManager::getFieldName($visit->getId(), "visit_type"), "1", $visit->visit_type)}
	                </td>
	                <td width="30%">
	                	{CHtml::textField(CProtocolManager::getFieldName($visit->getId(), "matter_text"), $visit->matter_text, "", "", 'style="width: 100%;"')}
	                </td>
	                <td width="10%" style="text-align:center;">
	                	{CHtml::checkBox(CProtocolManager::getFieldName($visit->getId(), "skip"), "1")}
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