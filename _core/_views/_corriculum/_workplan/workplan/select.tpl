{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Выбор учебного плана</h2>
    {CHtml::helpForCurrentPage()}
    
    <form action="workplans.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        {CHtml::hiddenField("action", "copyWorkPlan")}
        {CHtml::activeHiddenField("id", $plan)}
        
		<div class="control-group">
	        Выберите учебный план
	        <div class="controls">
	            {CHtml::activeDropDownList("corriculum_discipline_id", $plan, $items)}
	            {CHtml::error("corriculum_discipline_id", $plan)}
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