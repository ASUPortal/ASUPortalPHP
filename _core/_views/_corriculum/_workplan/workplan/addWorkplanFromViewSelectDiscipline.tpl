{extends file="_core.3col.tpl"}
{block name="asu_center"}
	<form action="workplans.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        {CHtml::hiddenField("action", "AddFromView_CreateWorkPlan")}
	    <div class="modal-header">
	        <h3 id="myModalLabel">Выбор из списка</h3>
	    </div>
	    <div class="modal-body">
	    	{$selectedFirst = false}
	        {foreach $items->getItems() as $key=>$value}
	        	<label class="radio">
	        		<input type="radio" value="{$key}" name="selected[]" {if !$selectedFirst}checked{$selectedFirst = true}{/if}/>
	        			{$value}
	        	</label>
	        {/foreach}
	    </div>
	    <div class="control-group">
	    	<div class="controls">
	    		{CHtml::submit("Выбрать", false)}
	    	</div>
	    </div>
	</form>
{/block}

{block name="asu_right"}
	{include file="_corriculum/_workplan/workplan/add.right.tpl"}
{/block}