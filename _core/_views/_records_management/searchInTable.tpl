{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Поиск для записей: {implode(",&nbsp;", $items)} из таблицы {$tableFound}, в таблице {$table}</h2>
    {CHtml::helpForCurrentPage()}
    
    <form action="index.php" method="post" class="form-horizontal">
	    {CHtml::hiddenField("items", implode(",", $items))}
	    {CHtml::hiddenField("table", $table)}
	    {CHtml::hiddenField("action", "resultSearchInTable")}
		
		<div class="control-group">
		    {CHtml::label("Выберите поле, в котором искать найденные записи", "fieldSearch")}
		    <div class="controls">
			    {CHtml::dropDownList("fieldSearch", $fields, "")}
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