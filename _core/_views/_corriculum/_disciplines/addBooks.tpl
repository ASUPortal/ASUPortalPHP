{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление изданий из библиотеки</h2>

    {CHtml::helpForCurrentPage()}
    
    <table class="table table-striped table-bordered table-hover table-condensed">
    	<thead>
    		<tr>
    			<th>Результат добавления</th>
    		</tr>
    	</thead>
    	<tbody>
    		<tr>
    			<td>{$message}</td>
    		</tr>
    	</tbody>
    </table>

{/block}

{block name="asu_right"}
	{include file="_corriculum/_disciplines/common.right.tpl"}
{/block}