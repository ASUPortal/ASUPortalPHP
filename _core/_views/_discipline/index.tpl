{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Справочник дисциплин</h2>

    {CHtml::helpForCurrentPage()}
    <a href="{$link}" target="_blank">Страница со списком дисциплин в библиотеке</a>
	{include file="_core.searchLocal.tpl"}
	
    {if ($disciplines->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
			<tr>
	            <th></th>
	            <th></th>
	            <th>#</th>
	            <th>{CHtml::tableOrder("name", $disciplines->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("library_code", $disciplines->getFirstItem())}</th>
	            <th>Кол-во книг</th>
        	</tr>
        	{counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $disciplines->getItems() as $discipline}
                <tr>
                    <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить дисциплину {$discipline->name}')) { location.href='?action=delete&id={$discipline->id}'; }; return false;"></a></td>
                    <td><a href="index.php?action=edit&id={$discipline->getId()}" class="icon-pencil" title="правка"></a></td>
					<td>{counter}</td>
					<td>{$discipline->name}</td>
					<td>{$discipline->library_code}</td>
					<td>{$discipline->books->getCount()}</td>
                </tr>
            {/foreach}
        </table>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
	{include file="_discipline/index.right.tpl"}
{/block}

