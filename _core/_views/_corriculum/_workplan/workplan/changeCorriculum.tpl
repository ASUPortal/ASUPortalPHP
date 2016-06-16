{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Выбор учебного плана для смены</h2>
    {CHtml::helpForCurrentPage()}
    
    <form action="workplans.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        {CHtml::hiddenField("action", "changeCorriculum")}
        {CHtml::hiddenField("plans", $plans)}
        
		<div class="control-group">
	        {CHtml::label("Выберите учебный план", "corriculum_id")}
	        <div class="controls">
	            {CHtml::dropDownList("corriculum_id", $items, "", null, "span12")}
	        </div>
	    </div>
        
        <div class="control-group">
            <div class="controls">
                {CHtml::submit("Выбрать", false)}
            </div>
        </div>
    </form>
{/block}

{block name="asu_right"}
	{include file="_corriculum/_workplan/workplan/common.right.tpl"}
{/block}