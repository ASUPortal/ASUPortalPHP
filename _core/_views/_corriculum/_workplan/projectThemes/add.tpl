{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление тем</h2>

    {CHtml::helpForCurrentPage()}

	<form action="workplanprojectthemes.php" method="post" class="form-horizontal">
	    <input type="hidden" name="action" value="saveGroup">
	    {CHtml::activeHiddenField("id", $group)}
	    {CHtml::activeHiddenField("plan_id", $group)}
	    {CHtml::activeHiddenField("type", $group)}
	
		<div class="control-group">
		    {CHtml::activeLabel("project_title", $group)}
		    <div class="controls">
		        {CHtml::activeTextBox("project_title", $group)}
		        {CHtml::error("project_title", $group)}
		    </div>
		</div>
	
	    <div class="control-group">
	        <div class="controls">
	            {CHtml::submit("Сохранить")}
	        </div>
	    </div>
	</form>

{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/projectThemes/common.right.tpl"}
{/block}