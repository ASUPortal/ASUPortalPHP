{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Проверки на антиплагиат</h2>

    {CHtml::helpForCurrentPage()}
    
{if $checks->getCount() == 0}
	Нет данных для отображения
{else}
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>{CHtml::tableOrder("check_date", $checks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("check_time", $checks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("borrowing_percent", $checks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("citations_percent", $checks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("originality_percent", $checks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("comments", $checks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("responsible_id", $checks->getFirstItem())}</th>
    </tr>
    {counter start=0 print=false}
    {foreach $checks->getItems() as $check}
        <tr>
            <td>{counter}</td>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить проверку')) { location.href='antiplagiat.php?action=delete&id={$check->id}'; }; return false;"></a></td>
            <td>
                <a href="antiplagiat.php?action=edit&id={$check->getId()}">
                    {$check->check_date|date_format:"d.m.Y"}
                </a>
            </td>
            <td>{$check->check_time}</td>
            <td>{$check->borrowing_percent}</td>
            <td>{$check->citations_percent}</td>
            <td>{$check->originality_percent}</td>
            <td>{$check->comments}</td>
            <td>{$check->responsible->getNameShort()}</td>
        </tr>
    {/foreach}
</table>
{/if}
    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
	{include file="_diploms/diplom_check/index.right.tpl"}
{/block}