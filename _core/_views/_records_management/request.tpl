{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Поиск дубликатов записей</h2>
    {CHtml::helpForCurrentPage()}
    
    <form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("table", $table)}
    {CHtml::hiddenField("base", $base)}
    {CHtml::hiddenField("action", "searchDuplicates")}
	
	<div class="control-group">
	    {CHtml::label("Название поля 1", "firstFieldName")}
	    <div class="controls">
		    {CHtml::dropDownList("firstFieldName", $fields, "")}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::label("Значение поля 1", "firstFieldValue")}
	    <div class="controls">
		    {CHtml::textField("firstFieldValue")}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::label("Название поля 2", "secondFieldName")}
	    <div class="controls">
		    {CHtml::dropDownList("secondFieldName", $fields, "")}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::label("Значение поля 2", "secondFieldValue")}
	    <div class="controls">
	    	{CHtml::textField("secondFieldValue")}
	    </div>
	</div>
			
    <div class="control-group">
        <div class="controls">
            <input name="" type="submit" class="btn" value="Найти">
        </div>
    </div>
</form>

{/block}

{block name="asu_right"}
	{include file="_records_management/common.right.tpl"}
{/block}