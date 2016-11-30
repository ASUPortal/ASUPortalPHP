{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Справочная система</h2>
{CHtml::helpForCurrentPage()}

    <table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th></th>
        <th>#</th>
        <th>{CHtml::tableOrder("title", $helps->getFirstItem())}</th>
        <th>{CHtml::tableOrder("url", $helps->getFirstItem())}</th>
        <th>{CHtml::tableOrder("wiki", $helps->getFirstItem())}</th>
        <th>{CHtml::tableOrder("content", $helps->getFirstItem())}</th>
    </tr>
    {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
    {foreach $helps->getItems() as $help}
        <tr>
            <td valign="top"><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить {$help->title}')) { location.href='?action=delete&id={$help->id}'; }; return false;"></td>
            <td valign="top">{counter}</td>
            <td valign="top"><a href="?action=edit&id={$help->id}">{$help->title}</a></td>
            <td valign="top">{$help->url}</td>
            <td valign="top">{$help->wiki}</td>
            <td valign="top">{$help->content}</td>
        </tr>
    {/foreach}
</table>

{CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_help/index.right.tpl"}
{/block}