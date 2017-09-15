{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Выбор базы данных</h2>
    {CHtml::helpForCurrentPage()}
    
<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "tables")}
	
	<div class="control-group">
	    {CHtml::label("Выберите название базы данных", "base")}
	    <div class="controls">
		    {CHtml::dropDownList("base", $bases, "")}
	    </div>
	</div>
	
    <div class="control-group">
        <div class="controls">
            <input name="" type="submit" class="btn" value="Выбрать">
        </div>
    </div>
</form>

{/block}

{block name="asu_right"}
	{include file="_records_management/common.right.tpl"}
{/block}