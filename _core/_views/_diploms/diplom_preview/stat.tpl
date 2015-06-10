{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Статистика по предзащитам</h2>

    {CHtml::helpForCurrentPage()}
    
<script>
    jQuery(document).ready(function(){
    	var isArchive = false;
    	{if $isArchive}
    		isArchive = true;
    	{/if}
    });
</script>
    
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>#</th>
            <th>Дата предзащиты</th>
            <th>Количество предзащит</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $previews as $key => $value}
        <tr>
            <td>{counter}</td>
			<td>{$key}</td>
            <td>{$value}</td>
        </tr>
        {/foreach}
    </table>
    
    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
	{include file="_diploms/diplom_preview/edit.right.tpl"}
{/block}