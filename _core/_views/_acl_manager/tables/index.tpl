{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Таблицы контроля доступа</h2>

    {CHtml::helpForCurrentPage()}

<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <th></th>
        <th>#</th>
        <th>Таблица</th>
        <th>Название</th>
        <th>Комментарий</th>
        <th>Последнее обслуживание</th>
    </tr>
    {foreach $tables->getItems() as $table}
    <tr>
        <td valign="top"><a href="#" onclick="if (confirm('Действительно удалить таблицу {$table->title}')) { location.href='?action=delete&id={$table->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
        <td>{counter}</td>
        <td>{$table->table}</td>
        <td><a href="tables.php?action=edit&id={$table->getId()}">{$table->title}</a></td>
        <td>{$table->description}</td>
        <td>{$table->last_service}</td>
    </tr>
    {/foreach}
</table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_acl_manager/tables/index.right.tpl"}
{/block}