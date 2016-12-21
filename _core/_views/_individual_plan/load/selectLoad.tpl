{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Выбор типа нагрузки</h2>
    {CHtml::helpForCurrentPage()}
    
    <form action="load.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        {CHtml::hiddenField("action", "copyLoadWorks")}
        {CHtml::hiddenField("id", $load->getId())}
        {CHtml::hiddenField("type", $type)}
        
		<div class="control-group">
	        {CHtml::label("Выберите нагрузку", "load_id")}
	        <div class="controls">
	            {CHtml::dropDownList("load_id", $items)}
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