{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <script>
        jQuery(document).ready(function(){
        	jQuery("#selectAll").change(function(){
        		var items = jQuery("input[name='selectedInView[]']")
                for (var i = 0; i < items.length; i++) {
                    items[i].checked = this.checked;
                }
            });
        });
    </script>
    
	<h2>Найдено записей: {$records->getCount()}, в таблице {$tableFound}, по полям {$firstFieldName}={$firstFieldValue} и {$secondFieldName}={$secondFieldValue}</h2>
    {CHtml::helpForCurrentPage()}
    
	{if $records->getCount() == 0}
		Нет объектов для отображения
	{else}
		<form action="index.php" method="post" id="mainView">
			{CHtml::hiddenField("action", "searchInTable")}
			{CHtml::hiddenField("tableFound", $tableFound)}
			
		    <table class="table table-striped table-bordered table-hover table-condensed">
		        <tr>
		            <th></th>
		            <th>#</th>
		            <th><input type="checkbox" id="selectAll" checked></th>
		            <th>ID записи</th>
		        </tr>
		        {counter start=0 print=false}
		        {foreach $records->getItems() as $record}
		        <tr>
		            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить запись {$record->getId()}')) { location.href='?action=delete&id={$record->getId()}&table={$tableFound}'; }; return false;"></a></td>
		            <td>{counter}</td>
		            <td><input type="checkbox" value="{$record->getId()}" name="selectedInView[]" checked></td>
		            <td>{$record->getId()}</td>
		        </tr>
		        {/foreach}
		    </table>
		    
		    <div class="control-group">
			    {CHtml::label("Выберите название таблицы, в которой искать найденные записи", "table")}
			    <div class="controls">
				    {CHtml::dropDownList("table", $tables, "")}
			    </div>
			</div>
		    
		    <div class="control-group">
		        <div class="controls">
		            <input name="" type="submit" class="btn" value="Выбрать">
		        </div>
		    </div>
	    </form>
    {/if}
{/block}

{block name="asu_right"}
	{include file="_records_management/common.right.tpl"}
{/block}
