{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование образовательной технологии рабочей программы</h2>

    {CHtml::helpForCurrentPage()}
    
	<form action="workplancontent.php" method="post" enctype="multipart/form-data" class="form-horizontal">
	    {CHtml::hiddenField("action", "saveTechnologies")}
	    {CHtml::activeHiddenField("id", $object)}
	    {CHtml::activeHiddenField("load_id", $object)}
	
	    {CHtml::errorSummary($object)}
	
	<div class="control-group">
	    {CHtml::activeLabel("technology_id", $object)}
	    <div class="controls">
	        {CHtml::activeLookup("technology_id", $object, "corriculum_education_technologies")}
	        {CHtml::error("technology_id", $object)}
	    </div>
	</div>
	
	<div class="control-group">
		{CHtml::activeLabel("value", $object)}
		<div class="controls">
			{CHtml::activeTextField("value", $object)}
			{CHtml::error("value", $object)}
		</div>
	</div>
	
	<div class="control-group">
		{CHtml::activeLabel("ordering", $object)}
		<div class="controls">
			{CHtml::activeTextField("ordering", $object)}
			{CHtml::error("ordering", $object)}
		</div>
	</div>
	
	    <div class="control-group">
	        <div class="controls">
	            {CHtml::submit("Сохранить", false)}
	        </div>
	    </div>
	</form>

{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/content/common.right.tpl"}
{/block}