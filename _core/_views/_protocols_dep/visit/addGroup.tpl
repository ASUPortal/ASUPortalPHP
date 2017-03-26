{extends file="_core.component.tpl"}

{block name="asu_center"}
    {CHtml::helpForCurrentPage()}
    
    <form action="visit.php" method="post" class="form-horizontal">

    {CHtml::hiddenField("action", "saveAdd")}
    {CHtml::hiddenField("id", $protocol->getId())}
    
    {CHtml::errorSummary($protocol)}
    
		<table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th>#</th>
	            <th>Преподаватель</th>
	            <th>Посещение</th>
	            <th>Причина отсутствия</th>
	        </tr>
	        {counter start=0 print=false}
	        {foreach $persons as $person}
	            <tr>
	                <td>{counter}</td>
	                <td width="50%">
	                    {CHtml::label($person->getName(), "", true)}
	                </td>
	                <td width="10%" style="text-align:center;">
	                    {CHtml::checkBox(CProtocolManager::getFieldName($person->getId(), "visit_type"), "1")}
	                </td>
	                <td width="40%">
	                	{CHtml::textField(CProtocolManager::getFieldName($person->getId(), "matter_text"), "", "", "", 'style="width: 100%;"')}
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