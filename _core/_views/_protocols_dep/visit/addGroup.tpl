{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Посещаемость</h2>

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
	        </tr>
	        {counter start=0 print=false}
	        {foreach $persons as $person}
	            <tr>
	                <td>{counter}</td>
	                <td width="80%">
	                    {CHtml::textField($person->getName(), $person->getName(), "", "", 'style="width: 100%;"')}
	                </td>
	                <td align="left" width="20%">
	                    {CHtml::checkBox($person->getId(), "1")}
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