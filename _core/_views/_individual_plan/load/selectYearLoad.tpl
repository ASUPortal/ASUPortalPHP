{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Выбор учебного года для копирования нагрузки</h2>
    {CHtml::helpForCurrentPage()}
    
    <form action="load.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        {CHtml::hiddenField("action", "copyLoad")}
        {CHtml::hiddenField("id", $load->getId())}
        
		<div class="control-group">
	        {CHtml::label("Выберите учебный год", "year_id")}
	        <div class="controls">
	            {CHtml::dropDownList("year_id", $items, $load->year_id)}
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
    {include file="_individual_plan/load/common.right.tpl"}
{/block}