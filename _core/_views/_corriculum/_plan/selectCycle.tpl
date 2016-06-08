{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Выбор цикла для смены</h2>
    {CHtml::helpForCurrentPage()}
    
    <form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        {CHtml::hiddenField("action", "changeCycle")}
        {CHtml::hiddenField("id", $corriculum->getId())}
        {CHtml::hiddenField("disciplines", $disciplines)}
        
		<div class="control-group">
	        {CHtml::label("Выберите цикл", "cycle_id")}
	        <div class="controls">
	            {CHtml::dropDownList("cycle_id", $items, "", null, "span12")}
	        </div>
	    </div>
        
        <div class="control-group">
            <div class="controls">
                {CHtml::submit("Сменить", false)}
            </div>
        </div>
    </form>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_plan/common.right.tpl"}
{/block}