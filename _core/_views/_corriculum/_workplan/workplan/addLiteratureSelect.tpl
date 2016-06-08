{extends file="_core.3col.tpl"}
{block name="asu_center"}
	<form action="workplans.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        {CHtml::hiddenField("action", "saveLiterature")}
        {CHtml::hiddenField("type", $type)}
        {CHtml::hiddenField("plan", $plan)}
	    <div class="modal-header">
	        <h3 id="myModalLabel">Выбор из списка</h3>
	    </div>
	    <div class="modal-body">
	        {foreach $items->getItems() as $key=>$value}
	        	<label class="checkbox">
	        		<input type="checkbox" value="{$key}" name="selected[{$key}]"/>
	        			{$value}
	        	</label>
	        {/foreach}
	    </div>
	    <div class="control-group">
	    	<div class="controls">
	    		{CHtml::submit("Сохранить", false)}
	    	</div>
	    </div>
	</form>
{/block}

{block name="asu_right"}
	{include file="_corriculum/_workplan/workplan/add.right.tpl"}
{/block}