{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Копирование рабочей программы</h2>
    {CHtml::helpForCurrentPage()}
    
    <form action="workplans.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        {CHtml::hiddenField("action", "copy")}
        {CHtml::hiddenField("id", $plan->getId())}
        
		<div class="control-group">
			{CHtml::label("Выберите название дисциплины из учебного плана", "corriculum_discipline_id")}
	        <div class="controls">
	        	{CHtml::dropDownList("corriculum_discipline_id", $items, $plan->corriculumDiscipline->getId(), null, "span12")}
	        </div>
	    </div>
        
        <div class="control-group">
            <div class="controls">
                {CHtml::submit("Копировать", false)}
            </div>
        </div>
    </form>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/workplan/common.right.tpl"}
{/block}